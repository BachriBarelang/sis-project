<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $subjectId = $this->route('subject')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],

            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('subjects', 'code')
                    ->ignore($subjectId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' =>
                'Nama mata pelajaran wajib diisi',

            'code.required' =>
                'Kode mata pelajaran wajib diisi',

            'code.unique' =>
                'Kode mata pelajaran sudah digunakan',
        ];
    }
}