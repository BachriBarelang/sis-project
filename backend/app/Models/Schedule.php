<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'teaching_assignment_id',
        'day',
        'start_time',
        'end_time',
    ];

    public function teachingAssignment()
    {
        return $this->belongsTo(
            TeachingAssignment::class
        );
    }
}