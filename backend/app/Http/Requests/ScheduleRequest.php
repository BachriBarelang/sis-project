<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleRequest extends FormRequest
{
        public function rules(): array
    {
        return [
            'teaching_assignment_id' => [
                'required',
                'exists:teaching_assignments,id'
            ],

            'day' => [
                'required'
            ],

            'start_time' => [
                'required',
                'date_format:H:i'
            ],

            'end_time' => [
                'required',
                'date_format:H:i',
                'after:start_time'
            ],
        ];
    }
}