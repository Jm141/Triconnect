<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Schedule;
use App\Models\User;
use App\Models\UserAccess;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PrincipalController extends Controller
{
    /**
     * Show principal dashboard
     */
    public function dashboard()
    {
        // Get statistics
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $totalSchedules = Schedule::where('status', 'active')->count();
        $todaySchedules = Schedule::where('status', 'active')
            ->whereJsonContains('day_of_week', Carbon::now()->format('l'))
            ->count();

        // Get recent notifications
        $recentNotifications = Notification::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('principal.dashboard', compact(
            'totalStudents',
            'totalTeachers', 
            'totalSchedules',
            'todaySchedules',
            'recentNotifications'
        ));
    }

    /**
     * Show student list
     */
    public function students()
    {
        $students = Student::with(['family'])
            ->orderBy('lastname')
            ->orderBy('firstname')
            ->paginate(20);

        return view('principal.students', compact('students'));
    }

    /**
     * Show teacher list
     */
    public function teachers()
    {
        $teachers = Teacher::orderBy('lastname')
            ->orderBy('firstname')
            ->paginate(20);

        return view('principal.teachers', compact('teachers'));
    }

    /**
     * Show schedule list
     */
    public function schedules()
    {
        $schedules = Schedule::with(['room', 'teacher'])
            ->orderBy('subject_name')
            ->paginate(20);

        return view('principal.schedules', compact('schedules'));
    }

    /**
     * Show notification form
     */
    public function notifications()
    {
        $notifications = Notification::orderBy('created_at', 'desc')
            ->paginate(20);

        return view('principal.notifications', compact('notifications'));
    }

    /**
     * Show create notification form
     */
    public function createNotification()
    {
        return view('principal.create-notification');
    }

    /**
     * Store notification
     */
    public function storeNotification(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'recipient_type' => 'required|in:all,teachers,parents',
            'priority' => 'required|in:low,medium,high,urgent'
        ]);

        try {
            DB::beginTransaction();

            // Create notification record
            $notification = Notification::create([
                'title' => $validated['title'],
                'message' => $validated['message'],
                'recipient_type' => $validated['recipient_type'],
                'priority' => $validated['priority'],
                'sent_by' => auth()->user()->userCode,
                'status' => 'sent'
            ]);

            // Get recipients based on type
            $recipients = [];
            
            if ($validated['recipient_type'] === 'all') {
                // Get all teachers and parents
                $teachers = UserAccess::where('access', 'teacher')->pluck('userCode');
                $parents = UserAccess::where('access', 'parent')->pluck('userCode');
                $recipients = $teachers->merge($parents);
            } elseif ($validated['recipient_type'] === 'teachers') {
                $recipients = UserAccess::where('access', 'teacher')->pluck('userCode');
            } elseif ($validated['recipient_type'] === 'parents') {
                $recipients = UserAccess::where('access', 'parent')->pluck('userCode');
            }

            // Store notification recipients (you might want to create a separate table for this)
            // For now, we'll just store the count
            $notification->update([
                'recipient_count' => $recipients->count()
            ]);

            DB::commit();

            return redirect()->route('principal.notifications')
                ->with('success', 'Notification sent successfully to ' . $recipients->count() . ' recipients!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to send notification: ' . $e->getMessage());
        }
    }

    /**
     * View notification details
     */
    public function viewNotification($id)
    {
        $notification = Notification::findOrFail($id);
        return view('principal.view-notification', compact('notification'));
    }

    /**
     * Delete notification
     */
    public function deleteNotification($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return redirect()->route('principal.notifications')
            ->with('success', 'Notification deleted successfully!');
    }

    /**
     * Get student statistics
     */
    public function studentStats()
    {
        $stats = [
            'total' => Student::count(),
            'by_grade' => Student::select('grade_level', DB::raw('count(*) as count'))
                ->groupBy('grade_level')
                ->get(),
            'active' => Student::where('status', 'active')->count(),
            'inactive' => Student::where('status', 'inactive')->count()
        ];

        return response()->json($stats);
    }

    /**
     * Get teacher statistics
     */
    public function teacherStats()
    {
        $stats = [
            'total' => Teacher::count(),
            'active' => Teacher::where('status', 'active')->count(),
            'inactive' => Teacher::where('status', 'inactive')->count(),
            'by_subject' => Schedule::select('subject_name', DB::raw('count(distinct teacher_staff_code) as count'))
                ->groupBy('subject_name')
                ->get()
        ];

        return response()->json($stats);
    }
} 