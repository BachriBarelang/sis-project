<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
    {
        return response()->json(
            Teacher::latest()->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'nip' => 'required|unique:teachers,nip',
                'name' => 'required',
                'gender' => 'required',
                'birth_date' => 'nullable|date',
                'phone' => 'nullable|string',
                'address' => 'nullable|string',
            ],
            [
                'nip.required' => 'NIP wajib diisi',
                'nip.unique' => 'NIP sudah digunakan',

                'name.required' => 'Nama guru wajib diisi',
                'gender.required' => 'Jenis kelamin wajib dipilih',
            ]
        );

        $teacher = Teacher::create($validated);

        return response()->json($teacher);
    }

    public function show(Teacher $teacher)
    {
        return response()->json($teacher);
    }

    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate(
            [
                'nip' => 'required|unique:teachers,nip,' . $teacher->id,
                'name' => 'required',
                'gender' => 'required',
                'birth_date' => 'nullable|date',
                'phone' => 'nullable|string',
                'address' => 'nullable|string',
            ],
            [
                'nip.required' => 'NIP wajib diisi',
                'nip.unique' => 'NIP sudah digunakan',

                'name.required' => 'Nama guru wajib diisi',
                'gender.required' => 'Jenis kelamin wajib dipilih',
            ]
        );

        $teacher->update($validated);

        return response()->json($teacher);
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->delete();

        return response()->json([
            'message' => 'Teacher deleted',
        ]);
    }
}