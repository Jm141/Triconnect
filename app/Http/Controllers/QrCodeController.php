<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Attendance;
use App\Models\Teacher;
use App\Models\Room;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    /**
     * Show QR code generation page for teachers
     */
    public function generateQr(Request $request)
    {
        $user = Auth::user();
        $teacherStaffCode = session('userAccess')->staff_code;
        
        // Get current time and day
        $now = Carbon::now();
        $currentDay = $now->format('l'); // Monday, Tuesday, etc.
        $currentTime = $now->format('H:i:s');
        
        // Get teacher's current schedule
        $currentSchedule = Schedule::where('teacher_staff_code', $teacherStaffCode)
            ->whereJsonContains('day_of_week', $currentDay)
            ->where('status', 'active')
            ->where('start_time', '<=', $currentTime)
            ->where('end_time', '>=', $currentTime)
            ->with(['room', 'subject'])
            ->first();
        
        // Get all teacher's schedules for today
        $todaysSchedules = Schedule::where('teacher_staff_code', $teacherStaffCode)
            ->whereJsonContains('day_of_week', $currentDay)
            ->where('status', 'active')
            ->with(['room', 'subject'])
            ->orderBy('start_time')
            ->get();
        
        return view('qr.generate', compact('currentSchedule', 'todaysSchedules', 'currentTime', 'currentDay'));
    }
    
    /**
     * Generate reusable QR code for teacher (contains only teacher userCode)
     */
    public function generateTeacherQr()
    {
        $teacherUserCode = session('userAccess')->userCode;
        
        // Create simple QR data with just teacher userCode - no additional fields
        $qrData = $teacherUserCode;
        
        $qrCode = QrCode::size(300)
            ->format('png')
            ->generate($qrData);
        
        // Get teacher info using staff_code (which is the same as userCode)
        $teacher = Teacher::where('staff_code', $teacherUserCode)->first();
        
        return view('qr.teacher_display', compact('qrCode', 'teacher', 'qrData'));
    }

    /**
     * Alternative QR generation with multiple options
     */
    public function generateAdvancedQr(Request $request)
    {
        $teacherStaffCode = session('userAccess')->staff_code;
        $teacher = Teacher::where('staff_code', $teacherStaffCode)->first();
        
        // Get all teacher's schedules
        $schedules = Schedule::where('teacher_staff_code', $teacherStaffCode)
            ->where('status', 'active')
            ->with(['room', 'subject'])
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        // Get current time info
        $now = Carbon::now();
        $currentDay = $now->format('l');
        $currentTime = $now->format('H:i:s');

        return view('qr.advanced_generate', compact('teacher', 'schedules', 'currentDay', 'currentTime'));
    }

    /**
     * Generate QR code with custom parameters
     */
    public function generateCustomQr(Request $request)
    {
        $request->validate([
            'qr_type' => 'required|in:teacher,room,schedule,custom',
            'schedule_id' => 'nullable|exists:schedules,id',
            'room_code' => 'nullable|string',
            'custom_data' => 'nullable|string',
            'qr_size' => 'nullable|integer|min:100|max:500',
            'expiry_minutes' => 'nullable|integer|min:1|max:1440'
        ]);

        $teacherStaffCode = session('userAccess')->staff_code;
        $qrSize = $request->qr_size ?? 300;
        $expiryMinutes = $request->expiry_minutes ?? 60;

        switch ($request->qr_type) {
            case 'teacher':
                $qrData = [
                    'teacher_staff_code' => $teacherStaffCode,
                    'type' => 'teacher_qr',
                    'generated_at' => now()->timestamp,
                    'expires_at' => now()->addMinutes($expiryMinutes)->timestamp
                ];
                break;

            case 'room':
                $roomCode = $request->room_code;
                $qrData = [
                    'teacher_staff_code' => $teacherStaffCode,
                    'room_code' => $roomCode,
                    'type' => 'room_qr',
                    'generated_at' => now()->timestamp,
                    'expires_at' => now()->addMinutes($expiryMinutes)->timestamp
                ];
                break;

            case 'schedule':
                $schedule = Schedule::with(['room', 'subject'])->findOrFail($request->schedule_id);
                
                // Verify teacher owns this schedule
                if ($schedule->teacher_staff_code !== $teacherStaffCode) {
                    return redirect()->back()->with('error', 'You can only generate QR codes for your own schedules.');
                }

                $qrData = [
                    'schedule_id' => $schedule->id,
                    'teacher_staff_code' => $schedule->teacher_staff_code,
                    'room_code' => $schedule->room_code,
                    'subject_name' => $schedule->subject_name,
                    'day_of_week' => $schedule->day_of_week,
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                    'type' => 'attendance_qr',
                    'generated_at' => now()->timestamp,
                    'expires_at' => now()->addMinutes($expiryMinutes)->timestamp
                ];
                break;

            case 'custom':
                $customData = $request->custom_data;
                $qrData = [
                    'teacher_staff_code' => $teacherStaffCode,
                    'custom_data' => $customData,
                    'type' => 'custom_qr',
                    'generated_at' => now()->timestamp,
                    'expires_at' => now()->addMinutes($expiryMinutes)->timestamp
                ];
                break;

            default:
                return redirect()->back()->with('error', 'Invalid QR type selected.');
        }

        // Generate QR code
        $qrCode = QrCode::size($qrSize)
            ->format('png')
            ->margin(2)
            ->errorCorrection('H')
            ->generate(json_encode($qrData));

        // Get teacher info
        $teacher = Teacher::where('staff_code', $teacherStaffCode)->first();

        return view('qr.custom_display', compact('qrCode', 'teacher', 'qrData', 'qrSize', 'expiryMinutes'));
    }

    /**
     * Generate quick QR for current class
     */
    public function generateQuickQr()
    {
        $teacherStaffCode = session('userAccess')->staff_code;
        $now = Carbon::now();
        $currentDay = $now->format('l');
        $currentTime = $now->format('H:i:s');

        // Find current active schedule
        $currentSchedule = Schedule::where('teacher_staff_code', $teacherStaffCode)
            ->whereJsonContains('day_of_week', $currentDay)
            ->where('status', 'active')
            ->where('start_time', '<=', $currentTime)
            ->where('end_time', '>=', $currentTime)
            ->with(['room', 'subject'])
            ->first();

        if (!$currentSchedule) {
            return redirect()->back()->with('error', 'No active class found at this time.');
        }

        // Generate QR with short expiry (15 minutes)
        $qrData = [
            'schedule_id' => $currentSchedule->id,
            'teacher_staff_code' => $currentSchedule->teacher_staff_code,
            'room_code' => $currentSchedule->room_code,
            'subject_name' => $currentSchedule->subject_name,
            'type' => 'quick_attendance_qr',
            'generated_at' => now()->timestamp,
            'expires_at' => now()->addMinutes(15)->timestamp
        ];

        $qrCode = QrCode::size(250)
            ->format('png')
            ->margin(1)
            ->errorCorrection('M')
            ->generate(json_encode($qrData));

        $teacher = Teacher::where('staff_code', $teacherStaffCode)->first();

        return view('qr.quick_display', compact('qrCode', 'currentSchedule', 'teacher', 'qrData'));
    }

    /**
     * Generate QR code for a specific schedule
     */
    public function generateScheduleQr($scheduleId)
    {
        $schedule = Schedule::with(['room', 'subject', 'teacher'])->findOrFail($scheduleId);
        
        // Check if teacher owns this schedule
        if ($schedule->teacher_staff_code !== session('userAccess')->staff_code) {
            return redirect()->back()->with('error', 'You can only generate QR codes for your own schedules.');
        }
        
        // Create QR data
        $qrData = [
            'schedule_id' => $schedule->id,
            'teacher_staff_code' => $schedule->teacher_staff_code,
            'room_code' => $schedule->room_code,
            'subject_name' => $schedule->subject_name,
            'day_of_week' => $schedule->day_of_week,
            'start_time' => $schedule->start_time,
            'end_time' => $schedule->end_time,
            'timestamp' => now()->timestamp,
            'type' => 'attendance_qr'
        ];
        
        $qrCode = QrCode::size(300)
            ->format('png')
            ->generate(json_encode($qrData));
        
        return view('qr.display', compact('qrCode', 'schedule', 'qrData'));
    }
    
    /**
     * Handle QR code scanning for attendance
     */
    public function scanQr(Request $request)
    {
        $request->validate([
            'qr_data' => 'required|string'
        ]);
        
        try {
            // Check if QR data is JSON (old format) or plain string (new format)
            $qrData = json_decode($request->qr_data, true);
            
            // If QR data is not JSON, treat it as plain teacher userCode
            if (!$qrData) {
                $teacherUserCode = $request->qr_data;
                
                // Validate that it's a valid userCode format (you can adjust this validation)
                if (empty($teacherUserCode) || strlen($teacherUserCode) < 3) {
                    return response()->json(['success' => false, 'message' => 'Invalid teacher code format']);
                }
                
                // Find teacher's current schedule based on time and day
                $currentTime = Carbon::now();
                $currentDay = $currentTime->format('l'); // Monday, Tuesday, etc.
                
                $schedule = Schedule::where('teacher_staff_code', $teacherUserCode)
                    ->whereJsonContains('day_of_week', $currentDay)
                    ->where('status', 'active')
                    ->where('start_time', '<=', $currentTime->format('H:i:s'))
                    ->where('end_time', '>=', $currentTime->format('H:i:s'))
                    ->with(['room', 'subject', 'teacher'])
                    ->first();
                
                if (!$schedule) {
                    return response()->json(['success' => false, 'message' => 'No active class found for this teacher at current time']);
                }
                
            } else {
                // Handle old JSON format for backward compatibility
                if (!isset($qrData['type'])) {
                    return response()->json(['success' => false, 'message' => 'Invalid QR code format']);
                }
                
                // Check if QR code has expired
                if (isset($qrData['expires_at'])) {
                    $expiryTime = Carbon::createFromTimestamp($qrData['expires_at']);
                    if (Carbon::now()->gt($expiryTime)) {
                        return response()->json(['success' => false, 'message' => 'QR code has expired']);
                    }
                }
                
                // Get current time info
                $currentTime = Carbon::now();
                $currentDay = $currentTime->format('l'); // Monday, Tuesday, etc.
                
                // Handle different QR types
                if ($qrData['type'] === 'teacher_qr') {
                    // Teacher QR - find current schedule dynamically
                    $teacherStaffCode = $qrData['teacher_staff_code'];
                    
                    // Find teacher's current schedule based on time and day
                    $schedule = Schedule::where('teacher_staff_code', $teacherStaffCode)
                        ->whereJsonContains('day_of_week', $currentDay)
                        ->where('status', 'active')
                        ->where('start_time', '<=', $currentTime->format('H:i:s'))
                        ->where('end_time', '>=', $currentTime->format('H:i:s'))
                        ->with(['room', 'subject', 'teacher'])
                        ->first();
                    
                    if (!$schedule) {
                        return response()->json(['success' => false, 'message' => 'No active class found for this teacher at current time']);
                    }
                    
                } elseif ($qrData['type'] === 'room_qr') {
                    // Room QR - find schedule by room and current time
                    $roomCode = $qrData['room_code'];
                    $teacherStaffCode = $qrData['teacher_staff_code'];
                    
                    $schedule = Schedule::where('teacher_staff_code', $teacherStaffCode)
                        ->where('room_code', $roomCode)
                        ->whereJsonContains('day_of_week', $currentDay)
                        ->where('status', 'active')
                        ->where('start_time', '<=', $currentTime->format('H:i:s'))
                        ->where('end_time', '>=', $currentTime->format('H:i:s'))
                        ->with(['room', 'subject', 'teacher'])
                        ->first();
                    
                    if (!$schedule) {
                        return response()->json(['success' => false, 'message' => 'No active class found in this room at current time']);
                    }
                    
                } elseif ($qrData['type'] === 'attendance_qr' || $qrData['type'] === 'quick_attendance_qr') {
                    // Schedule-specific QR - use the schedule from QR data
                    $schedule = Schedule::find($qrData['schedule_id']);
                    if (!$schedule) {
                        return response()->json(['success' => false, 'message' => 'Schedule not found']);
                    }
                    
                    // Check if current time is within schedule time
                    $scheduleStart = Carbon::parse($schedule->start_time);
                    $scheduleEnd = Carbon::parse($schedule->end_time);
                    $currentTimeOnly = Carbon::parse($currentTime->format('H:i:s'));
                    
                    if ($currentTimeOnly < $scheduleStart || $currentTimeOnly > $scheduleEnd) {
                        return response()->json(['success' => false, 'message' => 'QR code is only valid during class hours']);
                    }
                    
                } elseif ($qrData['type'] === 'custom_qr') {
                    // Custom QR - create attendance with custom data
                    $teacherStaffCode = $qrData['teacher_staff_code'];
                    $customData = $qrData['custom_data'];
                    
                    // Find any active schedule for the teacher
                    $schedule = Schedule::where('teacher_staff_code', $teacherStaffCode)
                        ->whereJsonContains('day_of_week', $currentDay)
                        ->where('status', 'active')
                        ->where('start_time', '<=', $currentTime->format('H:i:s'))
                        ->where('end_time', '>=', $currentTime->format('H:i:s'))
                        ->with(['room', 'subject', 'teacher'])
                        ->first();
                    
                    if (!$schedule) {
                        return response()->json(['success' => false, 'message' => 'No active class found for custom QR scan']);
                    }
                    
                } else {
                    return response()->json(['success' => false, 'message' => 'Invalid QR code type']);
                }
            }
            
            // Get current user
            $user = Auth::user();
            
            // Check if already scanned today
            $existingAttendance = Attendance::where('userCode', $user->userCode)
                ->where('schedule_id', $schedule->id)
                ->whereDate('time_scan', $currentTime->toDateString())
                ->first();
            
            if ($existingAttendance) {
                return response()->json(['success' => false, 'message' => 'Attendance already recorded for this class']);
            }
            
            // Create attendance record
            $attendance = Attendance::create([
                'userCode' => $user->userCode,
                'roomCode' => $schedule->room_code,
                'role' => 'student',
                'status' => 'logged_in',
                'time_scan' => $currentTime,
                'subject_name' => $schedule->subject_name,
                'teacher_staff_code' => $schedule->teacher_staff_code,
                'schedule_id' => $schedule->id,
                'attendance_type' => 'qr_scan',
                'qr_data' => $request->qr_data
            ]);
            
            return response()->json([
                'success' => true, 
                'message' => 'Attendance recorded successfully!',
                'data' => [
                    'subject' => $schedule->subject_name,
                    'room' => $schedule->room->name ?? $schedule->room_code,
                    'teacher' => $schedule->teacher->firstname . ' ' . $schedule->teacher->lastname,
                    'time' => $currentTime->format('H:i:s'),
                    'qr_type' => isset($qrData['type']) ? $qrData['type'] : 'simple_teacher_qr'
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error processing QR code: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Show attendance history for a teacher
     */
    public function attendanceHistory(Request $request)
    {
        $teacherStaffCode = session('userAccess')->staff_code;
        
        $query = Attendance::with(['schedule', 'subject', 'teacher'])
            ->where('teacher_staff_code', $teacherStaffCode)
            ->where('attendance_type', 'qr_scan');
        
        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('time_scan', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('time_scan', '<=', $request->end_date);
        }
        
        // Filter by subject
        if ($request->has('subject') && $request->subject) {
            $query->where('subject_name', $request->subject);
        }
        
        $attendances = $query->orderBy('time_scan', 'desc')->paginate(20);
        $subjects = collect([]); // Temporary fix - empty collection
        
        return view('qr.history', compact('attendances', 'subjects'));
    }
}
