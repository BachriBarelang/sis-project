<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        return response()->json(
            Student::latest()->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'nis' => 'required|unique:students,nis',
                'name' => 'required',
                'gender' => 'required',
                'birth_date' => 'nullable|date',
                'address' => 'nullable|string',
            ],
            [
                'nis.required' => 'NIS wajib diisi',
                'nis.unique' => 'NIS sudah digunakan',
                'name.required' => 'Nama siswa wajib diisi',
                'gender.required' => 'Jenis kelamin wajib dipilih',
            ]
        );

        $student = Student::create($validated);

        return response()->json($student);
    }

    public function show(Student $student)
    {
        return response()->json($student);
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate(
            [
                'nis' => 'required|unique:students,nis,' . $student->id,
                'name' => 'required',
                'gender' => 'required',
                'birth_date' => 'nullable|date',
                'address' => 'nullable|string',
            ],
            [
                'nis.required' => 'NIS wajib diisi',
                'nis.unique' => 'NIS sudah digunakan',
                'name.required' => 'Nama siswa wajib diisi',
                'gender.required' => 'Jenis kelamin wajib dipilih',
            ]
        );

        $student->update($validated);

        return response()->json($student);
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return response()->json([
            'message' => 'Student deleted',
        ]);
    }
}