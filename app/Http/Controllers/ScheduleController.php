<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Teacher;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Schedule::with(['teacher', 'room']);

        // Filter by teacher if specified
        if ($request->has('teacher') && $request->teacher) {
            $query->where('teacher_staff_code', $request->teacher);
        }

        // Filter by day if specified
        if ($request->has('day') && $request->day) {
            $query->where('day_of_week', $request->day);
        }

        // Filter by room if specified
        if ($request->has('room') && $request->room) {
            $query->where('room_code', $request->room);
        }

        // Filter by status if specified
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $schedules = $query->orderBy('day_of_week')
                          ->orderBy('start_time')
                          ->paginate(15);

        $teachers = Teacher::where('status', 'active')->get();
        $rooms = Room::where('status', 'active')->get();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        return view('schedules.index', compact('schedules', 'teachers', 'rooms', 'days'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get current teacher's staff code from session
        $currentTeacherCode = session('userAccess')->userCode ?? null;
        
        $teachers = Teacher::where('status', 'active')->get();
        $rooms = Room::where('status', 'active')->get();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $gradeLevels = ['Kinder', 'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6', 'Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'];

        return view('schedules.create', compact('teachers', 'rooms', 'days', 'gradeLevels', 'currentTeacherCode'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'teacher_staff_code' => 'required|exists:teachers,staff_code',
            'subject_name' => 'required|string|max:255',
            'room_code' => 'required|exists:rooms,room_code',
            'day_of_week' => 'required|array|min:1',
            'day_of_week.*' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'grade_level' => 'nullable|string|max:50',
            'section' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Auto-set teacher code from session if not provided
        if (empty($request->teacher_staff_code) && session('userAccess')) {
            $request->merge(['teacher_staff_code' => session('userAccess')->userCode]);
        }

        // Check for schedule conflicts for each selected day
        $selectedDays = $request->day_of_week;
        $conflicts = [];

        foreach ($selectedDays as $day) {
            $dayConflicts = Schedule::where('teacher_staff_code', $request->teacher_staff_code)
                ->whereJsonContains('day_of_week', $day)
                ->where('room_code', $request->room_code)
                ->where(function($query) use ($request) {
                    $query->where(function($q) use ($request) {
                        $q->where('start_time', '<=', $request->start_time)
                          ->where('end_time', '>', $request->start_time);
                    })->orWhere(function($q) use ($request) {
                        $q->where('start_time', '<', $request->end_time)
                          ->where('end_time', '>=', $request->end_time);
                    })->orWhere(function($q) use ($request) {
                        $q->where('start_time', '>=', $request->start_time)
                          ->where('end_time', '<=', $request->end_time);
                    });
                })->get();

            if ($dayConflicts->count() > 0) {
                $conflicts[] = "Conflict found for $day";
            }
        }

        if (!empty($conflicts)) {
            return redirect()->back()
                ->withErrors(['conflict' => implode(', ', $conflicts)])
                ->withInput();
        }

        try {
            // Create schedule with multiple days
            $schedule = Schedule::create([
                'teacher_staff_code' => $request->teacher_staff_code,
                'subject_name' => $request->subject_name,
                'room_code' => $request->room_code,
                'day_of_week' => $selectedDays, // Store as JSON array
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'grade_level' => $request->grade_level,
                'section' => $request->section,
                'status' => 'active',
                'notes' => $request->notes,
            ]);

            return redirect()->route('schedules.index')
                ->with('success', 'Schedule created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create schedule: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        $schedule->load(['teacher', 'room']);
        return view('schedules.show', compact('schedule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule)
    {
        $teachers = Teacher::where('status', 'active')->get();
        $rooms = Room::where('status', 'active')->get();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $gradeLevels = ['Kinder', 'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6', 'Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'];

        return view('schedules.edit', compact('schedule', 'teachers', 'rooms', 'days', 'gradeLevels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        $validator = Validator::make($request->all(), [
            'teacher_staff_code' => 'required|exists:teachers,staff_code',
            'subject_name' => 'required|string|max:255',
            'room_code' => 'required|exists:rooms,room_code',
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'grade_level' => 'nullable|string|max:50',
            'section' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check for schedule conflicts (excluding current schedule)
        $conflicts = Schedule::where('id', '!=', $schedule->id)
            ->where('teacher_staff_code', $request->teacher_staff_code)
            ->where('day_of_week', $request->day_of_week)
            ->where('room_code', $request->room_code)
            ->where(function($query) use ($request) {
                $query->where(function($q) use ($request) {
                    $q->where('start_time', '<=', $request->start_time)
                      ->where('end_time', '>', $request->start_time);
                })->orWhere(function($q) use ($request) {
                    $q->where('start_time', '<', $request->end_time)
                      ->where('end_time', '>=', $request->end_time);
                })->orWhere(function($q) use ($request) {
                    $q->where('start_time', '>=', $request->start_time)
                      ->where('end_time', '<=', $request->end_time);
                });
            })
            ->where('status', 'active')
            ->get();

        if ($conflicts->count() > 0) {
            return redirect()->back()
                ->withErrors(['conflict' => 'Schedule conflict detected. The selected time slot overlaps with an existing schedule.'])
                ->withInput();
        }

        $schedule->update($request->all());

        return redirect()->route('schedules.index')
            ->with('success', 'Schedule updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('schedules.index')
            ->with('success', 'Schedule deleted successfully!');
    }

    /**
     * Display weekly schedule view
     */
    public function weeklyView(Request $request)
    {
        $query = Schedule::with(['teacher', 'room']);

        // Filter by teacher if specified
        if ($request->has('teacher') && $request->teacher) {
            $query->where('teacher_staff_code', $request->teacher);
        }

        $schedules = $query->where('status', 'active')
                          ->orderBy('day_of_week')
                          ->orderBy('start_time')
                          ->get()
                          ->groupBy('day_of_week');

        $teachers = Teacher::where('status', 'active')->get();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        return view('schedules.weekly', compact('schedules', 'teachers', 'days'));
    }

    /**
     * Toggle schedule status
     */
    public function toggleStatus(Schedule $schedule)
    {
        $schedule->status = $schedule->status === 'active' ? 'inactive' : 'active';
        $schedule->save();

        return redirect()->back()
            ->with('success', 'Schedule status updated successfully!');
    }
}
