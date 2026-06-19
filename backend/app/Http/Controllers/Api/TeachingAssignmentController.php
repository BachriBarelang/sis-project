<?php

namespace App\Http\Controllers\Api;

use App\Models\TeachingAssignment;
use App\Http\Controllers\Controller;
use App\Http\Requests\TeachingAssignmentRequest;

class TeachingAssignmentController
    extends Controller
{
    public function index()
    {
        return TeachingAssignment::with([
            'teacher',
            'subject',
            'schoolClass',
        ])
            ->latest()
            ->get();
    }

    public function store(
        TeachingAssignmentRequest $request
    ) {
        return TeachingAssignment::create(
            $request->validated()
        )->load([
            'teacher',
            'subject',
            'schoolClass',
        ]);
    }

    public function show(
        TeachingAssignment
        $teachingAssignment
    ) {
        return $teachingAssignment->load([
            'teacher',
            'subject',
            'schoolClass',
        ]);
    }

    public function update(
        TeachingAssignmentRequest $request,
        TeachingAssignment
        $teachingAssignment
    ) {
        $teachingAssignment->update(
            $request->validated()
        );

        return $teachingAssignment
            ->fresh()
            ->load([
                'teacher',
                'subject',
                'schoolClass',
            ]);
    }

    public function destroy(
        TeachingAssignment
        $teachingAssignment
    ) {
        $teachingAssignment->delete();

        return response()->json([
            'message' => 'Deleted',
        ]);
    }
}