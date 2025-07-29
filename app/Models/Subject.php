<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_code',
        'subject_name',
        'description',
        'status'
    ];

    // Relationship with Schedule
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'subject_name', 'subject_name');
    }

    // Relationship with Attendance
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'subject_name', 'subject_name');
    }

    // Scope for active subjects
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
