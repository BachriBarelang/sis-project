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
        $validated = $request->validate([
            'nip' => 'required|unique:teachers',
            'name' => 'required',
            'gender' => 'required',
        ]);

        $teacher = Teacher::create([
            'nip' => $request->nip,
            'name' => $request->name,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return response()->json($teacher);
    }

    public function show(Teacher $teacher)
    {
        return response()->json($teacher);
    }

    public function update(
        Request $request,
        Teacher $teacher
    ) {
        $teacher->update([
            'nip' => $request->nip,
            'name' => $request->name,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

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