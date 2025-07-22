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
    ];

    public $timestamps = true;
}
