<?php

namespace App\Http\Controllers\Api;

use App\Models\Subject;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubjectRequest;

class SubjectController extends Controller
{
    public function index()
    {
        return Subject::latest()->get();
    }

    public function store(
        SubjectRequest $request
    ) {
        return Subject::create(
            $request->validated()
        );
    }

    public function show(
        Subject $subject
    ) {
        return $subject;
    }

    public function update(
        SubjectRequest $request,
        Subject $subject
    ) {
        $subject->update(
            $request->validated()
        );

        return $subject->fresh();
    }

    public function destroy(
        Subject $subject
    ) {
        $subject->delete();

        return response()->json([
            'message' => 'Deleted',
        ]);
    }
}