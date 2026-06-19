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
        $validated = $request->validate([
            'nis' => 'required|unique:students',
            'name' => 'required',
            'gender' => 'required',
        ]);

        $student = Student::create([
            'nis' => $request->nis,
            'name' => $request->name,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'address' => $request->address,
        ]);

        return response()->json($student);
    }

    public function show(Student $student)
    {
        return response()->json($student);
    }

    public function update(
        Request $request,
        SchoolClass $schoolClass
    )
    {
        dd([
            'model' => $schoolClass,
            'payload' => $request->all(),
        ]);
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return response()->json([
            'message' => 'Student deleted',
        ]);
    }
}