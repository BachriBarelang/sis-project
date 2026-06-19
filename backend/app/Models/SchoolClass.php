<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SchoolClass extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'name',
        'level',
        'homeroom_teacher_id',
    ];

    public function homeroomTeacher()
    {
        return $this->belongsTo(
            Teacher::class,
            'homeroom_teacher_id'
        );
    }

    public function teachingAssignments()
    {
        return $this->hasMany(
            TeachingAssignment::class,
            'class_id'
        );
    }

    public function students()
    {
        return $this->hasMany(
            Student::class,
            'class_id'
        );
    }
}