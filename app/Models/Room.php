<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'room_code','status'];

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'room_code', 'room_code');
    }
}
