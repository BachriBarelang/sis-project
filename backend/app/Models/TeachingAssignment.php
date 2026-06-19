<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TeachingAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'subject_id',
        'class_id',
        'academic_year',
        'semester',
    ];

    public function teacher()
    {
        return $this->belongsTo(
            Teacher::class
        );
    }

    public function subject()
    {
        return $this->belongsTo(
            Subject::class
        );
    }

    public function schoolClass()
    {
        return $this->belongsTo(
            SchoolClass::class,
            'class_id'
        );
    }

        public function schedules()
    {
        return $this->hasMany(
            Schedule::class,
            'teaching_assignment_id'
        );
    }
}