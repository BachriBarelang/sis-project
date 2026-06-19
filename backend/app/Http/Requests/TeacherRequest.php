<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TeacherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nip' => ['required', 'max:50'],
            'name' => ['required', 'max:255'],
            'gender' => ['required', 'in:L,P'],
            'birth_date' => ['required', 'date'],
            'phone' => ['nullable', 'max:20'],
            'address' => ['nullable'],
        ];
    }
}
