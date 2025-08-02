<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use App\Models\UserAccess;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserTeacherController extends Controller
{
    /**
     * Show attendance dashboard with current class and historical data.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function attendanceDashboard(Request $request)
    {
        // Get current user (teacher)
        $user = auth()->user();
        $userAccess = UserAccess::where('userCode', $user->userCode)->first();
        
        if (!$userAccess || strpos($userAccess->access, 'teacher') === false) {
            return redirect()->back()->with('error', 'Access denied. Teachers only.');
        }

        $currentTime = Carbon::now();
        $currentDay = $currentTime->format('l'); // Monday, Tuesday, etc.

        // Get current active schedule for the teacher
        $currentSchedule = Schedule::where('teacher_staff_code', $userAccess->userCode ?? '')
            ->where('status', 'active')
            ->whereJsonContains('day_of_week', $currentDay)
            ->where('start_time', '<=', $currentTime->format('H:i:s'))
            ->where('end_time', '>=', $currentTime->format('H:i:s'))
            ->first();

        // Get current class attendance
        $currentClassAttendance = collect();
        if ($currentSchedule) {
            $currentClassAttendance = Attendance::where('schedule_id', $currentSchedule->id)
                ->where('status', 'logged_in')
                ->whereDate('time_scan', $currentTime->toDateString())
                ->with(['teacher', 'schedule'])
                ->get();
        }

        // Get today's schedules for the teacher
        $todaysSchedules = Schedule::where('teacher_staff_code', $userAccess->userCode ?? '')
            ->where('status', 'active')
            ->whereJsonContains('day_of_week', $currentDay)
            ->orderBy('start_time')
            ->get();

        // Get historical attendance data (last 30 days)
        $historicalAttendance = Attendance::where('teacher_staff_code', $userAccess->userCode ?? '')
            ->where('attendance_type', 'qr_scan')
            ->where('time_scan', '>=', $currentTime->subDays(30))
            ->with(['schedule'])
            ->orderByDesc('time_scan')
            ->get()
            ->groupBy(function($attendance) {
                return $attendance->time_scan->format('Y-m-d');
            });

        return view('user.attendanceDashboard', [
            'currentSchedule' => $currentSchedule,
            'currentClassAttendance' => $currentClassAttendance,
            'todaysSchedules' => $todaysSchedules,
            'historicalAttendance' => $historicalAttendance,
            'currentTime' => $currentTime,
            'currentDay' => $currentDay,
            'userAccess' => $userAccess
        ]);
    }

    /**
     * Get attendance for a specific schedule/class.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getClassAttendance(Request $request)
    {
        $scheduleId = $request->input('schedule_id');
        $date = $request->input('date', Carbon::now()->toDateString());

        $attendance = Attendance::where('schedule_id', $scheduleId)
            ->whereDate('time_scan', $date)
            ->where('status', 'logged_in')
            ->with(['teacher', 'schedule'])
            ->get();

        return response()->json([
            'success' => true,
            'attendance' => $attendance,
            'count' => $attendance->count()
        ]);
    }

    /**
     * Get historical attendance data with filters.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHistoricalAttendance(Request $request)
    {
        $teacherStaffCode = $request->input('teacher_staff_code');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $subjectName = $request->input('subject_name');

        $query = Attendance::where('teacher_staff_code', $teacherStaffCode)
            ->where('attendance_type', 'qr_scan');

        if ($startDate && $endDate) {
            $query->whereBetween('time_scan', [$startDate, $endDate]);
        }

        if ($subjectName) {
            $query->where('subject_name', $subjectName);
        }

        $attendance = $query->with(['schedule'])
            ->orderByDesc('time_scan')
            ->get()
            ->groupBy(function($attendance) {
                return $attendance->time_scan->format('Y-m-d');
            });

        return response()->json([
            'success' => true,
            'attendance' => $attendance
        ]);
    }

    /**
     * Get attendance details for a specific day and subject.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDayDetails(Request $request)
    {
        $date = $request->input('date');
        $subjectName = $request->input('subject_name');
        $teacherStaffCode = $request->input('teacher_staff_code');

        $attendance = Attendance::where('teacher_staff_code', $teacherStaffCode)
            ->where('subject_name', $subjectName)
            ->whereDate('time_scan', $date)
            ->where('status', 'logged_in')
            ->get()
            ->map(function($record) {
                // Format the student name for display
                $displayName = 'Unknown';
                if ($record->name) {
                    $nameParts = explode('.', $record->name);
                    $displayName = count($nameParts) >= 2 
                        ? ucfirst($nameParts[0]) . ' ' . ucfirst($nameParts[1])
                        : ucfirst($record->name);
                }
                
                return [
                    'userCode' => $record->userCode,
                    'student_name' => $displayName,
                    'time_scan' => $record->time_scan,
                    'status' => $record->status
                ];
            });

        return response()->json([
            'success' => true,
            'attendance' => $attendance
        ]);
    }

    /**
     * Export attendance data to CSV.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportAttendance(Request $request)
    {
        $teacherStaffCode = $request->input('teacher_staff_code');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Attendance::where('teacher_staff_code', $teacherStaffCode)
            ->where('attendance_type', 'qr_scan');

        if ($startDate && $endDate) {
            $query->whereBetween('time_scan', [$startDate, $endDate]);
        }

        $attendance = $query->with(['schedule'])
            ->orderBy('time_scan')
            ->get();

        $filename = "attendance_export_{$startDate}_to_{$endDate}.csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($attendance) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'Date',
                'Subject',
                'Room',
                'Student Code',
                'Student Name',
                'Time Scanned',
                'Status'
            ]);

            foreach ($attendance as $record) {
                // Format the student name for export
                $displayName = 'Unknown';
                if ($record->name) {
                    $nameParts = explode('.', $record->name);
                    $displayName = count($nameParts) >= 2 
                        ? ucfirst($nameParts[0]) . ' ' . ucfirst($nameParts[1])
                        : ucfirst($record->name);
                }
                
                fputcsv($file, [
                    $record->time_scan->format('Y-m-d'),
                    $record->subject_name,
                    $record->roomCode,
                    $record->userCode,
                    $displayName,
                    $record->time_scan->format('H:i:s'),
                    $record->status
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export all attendance data for the current teacher to CSV.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportAttendanceCSV(Request $request)
    {
        // Get current user (teacher)
        $user = auth()->user();
        $userAccess = UserAccess::where('userCode', $user->userCode)->first();
        
        if (!$userAccess || strpos($userAccess->access, 'teacher') === false) {
            return redirect()->back()->with('error', 'Access denied. Teachers only.');
        }

        $teacherStaffCode = $userAccess->userCode;

        $attendance = Attendance::where('teacher_staff_code', $teacherStaffCode)
            ->where('attendance_type', 'qr_scan')
            ->with(['schedule'])
            ->orderBy('time_scan', 'desc')
            ->get();

        $filename = "attendance_export_" . date('Y-m-d_H-i-s') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($attendance) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'Date',
                'Subject',
                'Room',
                'Student Code',
                'Student Name',
                'Time Scanned',
                'Status',
                'Created At'
            ]);

            foreach ($attendance as $record) {
                // Format the student name for export
                $displayName = 'Unknown';
                if ($record->name) {
                    $nameParts = explode('.', $record->name);
                    $displayName = count($nameParts) >= 2 
                        ? ucfirst($nameParts[0]) . ' ' . ucfirst($nameParts[1])
                        : ucfirst($record->name);
                }
                
                fputcsv($file, [
                    $record->time_scan->format('Y-m-d'),
                    $record->subject_name ?? 'N/A',
                    $record->roomCode ?? 'N/A',
                    $record->userCode,
                    $displayName,
                    $record->time_scan->format('H:i:s'),
                    $record->status,
                    $record->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
} 