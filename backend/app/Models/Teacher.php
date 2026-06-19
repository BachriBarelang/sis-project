<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'nip',
        'name',
        'gender',
        'birth_date',
        'phone',
        'address',
    ];

    public function homeroomClass()
    {
        return $this->hasOne(
            SchoolClass::class,
            'homeroom_teacher_id'
        );
    }

    public function teachingAssignments()
    {
        return $this->hasMany(
            TeachingAssignment::class
        );
    }
}