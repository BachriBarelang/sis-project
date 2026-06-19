<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeachingAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('teaching_assignment')?->id;

        return [
            'teacher_id' => [
                'required',
                'exists:teachers,id',
            ],

            'subject_id' => [
                'required',
                'exists:subjects,id',
            ],

            'class_id' => [
                'required',
                'exists:classes,id',
            ],

            'academic_year' => [
                'required',
            ],

            'semester' => [
                'required',
                Rule::in([
                    'Ganjil',
                    'Genap',
                ]),
            ],
        ];
    }

    public function withValidator($validator)
    {
        $id = $this->route('teaching_assignment')?->id;

        $validator->after(function ($validator) use ($id) {

            $exists = \App\Models\TeachingAssignment::query()
                ->when(
                    $id,
                    fn ($q) => $q->where('id', '!=', $id)
                )
                ->where('teacher_id', $this->teacher_id)
                ->where('subject_id', $this->subject_id)
                ->where('class_id', $this->class_id)
                ->where('academic_year', $this->academic_year)
                ->where('semester', $this->semester)
                ->exists();

            if ($exists) {
                $validator->errors()->add(
                    'teacher_id',
                    'Data pengampu sudah terdaftar.'
                );
            }
        });
    }
}