<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_staff_code',
        'subject_name',
        'room_code',
        'day_of_week',
        'start_time',
        'end_time',
        'grade_level',
        'section',
        'status',
        'notes'
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'day_of_week' => 'array', // Cast to array for multiple days
    ];

    // Relationship with Teacher
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_staff_code', 'staff_code');
    }

    // Relationship with Room
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_code', 'room_code');
    }

    // Relationship with Subject
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_name', 'subject_name');
    }

    // Relationship with Attendance
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // Scope for active schedules
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope for specific day
    public function scopeForDay($query, $day)
    {
        return $query->whereJsonContains('day_of_week', $day);
    }

    // Scope for multiple days
    public function scopeForDays($query, $days)
    {
        return $query->where(function($q) use ($days) {
            foreach ($days as $day) {
                $q->orWhereJsonContains('day_of_week', $day);
            }
        });
    }

    // Scope for specific teacher
    public function scopeForTeacher($query, $teacherStaffCode)
    {
        return $query->where('teacher_staff_code', $teacherStaffCode);
    }

    // Scope for specific room
    public function scopeForRoom($query, $roomCode)
    {
        return $query->where('room_code', $roomCode);
    }

    // Get formatted time range
    public function getTimeRangeAttribute()
    {
        return $this->start_time->format('H:i') . ' - ' . $this->end_time->format('H:i');
    }

    // Check if schedule conflicts with another schedule
    public function conflictsWith($otherSchedule)
    {
        if ($this->day_of_week !== $otherSchedule->day_of_week) {
            return false;
        }

        if ($this->room_code !== $otherSchedule->room_code) {
            return false;
        }

        // Check time overlap
        $thisStart = $this->start_time;
        $thisEnd = $this->end_time;
        $otherStart = $otherSchedule->start_time;
        $otherEnd = $otherSchedule->end_time;

        return ($thisStart < $otherEnd && $thisEnd > $otherStart);
    }
}
