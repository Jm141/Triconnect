<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Teacher extends Model
{  
    use HasFactory;
    protected $table = 'teachers';
    protected $primaryKey = 'staff_code';
    protected $keyType = 'string';
    public $incrementing = false;
    

    protected $fillable = [
        'staff_code',
        'email',
        'phone',
        'address',
        'status',
        'age',
        'birth',
        'lastname',
        'firstname',
        'middlename',
        'username',
        'password',
        'id',
    ];

    public function user()
{
    return $this->hasOne(User::class, 'userCode', 'staff_code');
}
}