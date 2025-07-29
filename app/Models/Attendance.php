<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendance'; 

    protected $fillable = [
        'userCode',
        'roomCode',
        'role',
        'status',
        'time_scan',
        'subject_name',
        'teacher_staff_code',
        'schedule_id',
        'attendance_type',
        'qr_data'
    ];

    protected $casts = [
        'time_scan' => 'datetime',
    ];

    public $timestamps = true;

    // Relationship with Teacher
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_staff_code', 'staff_code');
    }

    // Relationship with Schedule
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    // Relationship with Subject
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_name', 'subject_name');
    }

    // Scope for QR scan attendance
    public function scopeQrScan($query)
    {
        return $query->where('attendance_type', 'qr_scan');
    }

    // Scope for specific teacher
    public function scopeForTeacher($query, $teacherStaffCode)
    {
        return $query->where('teacher_staff_code', $teacherStaffCode);
    }

    // Scope for specific subject
    public function scopeForSubject($query, $subjectName)
    {
        return $query->where('subject_name', $subjectName);
    }
}
