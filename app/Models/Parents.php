<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parents extends Model
{
    use HasFactory;
    protected $table = 'parents';
    protected $primaryKey = 'family_code';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['lname','mname','fname', 'number', 'status', 'email','family_code'];

    public function students()
{
    return $this->hasMany(Student::class, 'family_code', 'family_code');
}

public function family()
{
    return $this->belongsTo(Family::class, 'family_code', 'family_code');
}

}
