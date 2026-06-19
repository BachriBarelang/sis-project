<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SchoolClassRequest;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    /**
     * Display a listing of classes.
     */
    public function index()
    {
        return SchoolClass::with([
            'homeroomTeacher',
        ])
            ->withCount('students')
            ->latest()
            ->get();
    }

    /**
     * Store a newly created class.
     */
    public function store(SchoolClassRequest $request)
    {
        $schoolClass = SchoolClass::create(
            $request->validated()
        );

        return response()->json(
            $schoolClass->load('homeroomTeacher'),
            201
        );
    }

    /**
     * Display class detail.
     */
    public function show(string $id)
    {
        return SchoolClass::with([
            'homeroomTeacher',
        ])
            ->withCount('students')
            ->findOrFail($id);
    }

    /**
     * Update class.
     */
    public function update(
        SchoolClassRequest $request,
        string $id
    ) {
        $schoolClass = SchoolClass::findOrFail($id);

        $schoolClass->update(
            $request->validated()
        );

        return response()->json(
            $schoolClass->fresh('homeroomTeacher')
        );
    }

    /**
     * Delete class.
     */
    public function destroy(string $id)
    {
        $schoolClass = SchoolClass::findOrFail($id);

        $schoolClass->delete();

        return response()->json([
            'message' => 'Deleted',
        ]);
    }

    /**
     * Detail class with students.
     */
    public function detail(string $id)
    {
        $schoolClass = SchoolClass::with([
            'homeroomTeacher',
            'students',
        ])
            ->withCount('students')
            ->findOrFail($id);

        $unassignedStudents = Student::whereNull(
            'class_id'
        )
            ->orderBy('name')
            ->get();

        return response()->json([
            'class' => $schoolClass,
            'unassigned_students' => $unassignedStudents,
        ]);
    }

    /**
     * Assign students to class.
     */
    public function assignStudents(
        Request $request,
        string $id
    ) {
        $schoolClass = SchoolClass::findOrFail($id);

        $validated = $request->validate([
            'student_ids' => [
                'required',
                'array',
            ],
            'student_ids.*' => [
                'exists:students,id',
            ],
        ]);

        Student::whereIn(
            'id',
            $validated['student_ids']
        )->update([
            'class_id' => $schoolClass->id,
        ]);

        return response()->json([
            'message' => 'Students assigned successfully',
        ]);
    }

    /**
     * Remove student from class.
     */
    public function removeStudent(
        string $id,
        string $studentId
    ) {
        $schoolClass = SchoolClass::findOrFail($id);

        $student = Student::findOrFail(
            $studentId
        );

        if (
            $student->class_id !==
            $schoolClass->id
        ) {
            return response()->json([
                'message' => 'Student is not assigned to this class',
            ], 422);
        }

        $student->update([
            'class_id' => null,
        ]);

        return response()->json([
            'message' => 'Student removed successfully',
        ]);
    }
}