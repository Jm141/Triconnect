<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAccess extends Model
{
    /** @use HasFactory<\Database\Factories\UserAccessFactory> */
    use HasFactory;
    protected $primaryKey = 'userCode';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'user_access_links'; 

    protected $fillable = ['userCode', 'access','name','email'];

}
