<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Student extends Model
{
    use HasFactory;
    protected $table = 'students';
    protected $primaryKey = 'family_code';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        
        'family_code',
        'status',
        'age',
        'birth',
        'grade_level',
        'lastname',
        'firstname',
        'middlename',
        'id',
        'email',
    ];

    
   
    public function family()
    {
        return $this->hasOne(Family::class, 'family_code', 'family_code');
    }
    

    public function parents()
    {
        return $this->belongsTo(Parents::class, 'family_code', 'family_code');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'userCode', 'family_code');
    }
}

