<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScheduleRequest;
use App\Models\Schedule;
use App\Models\TeachingAssignment;

class ScheduleController extends Controller
{
    public function index()
    {
        return Schedule::with([
            'teachingAssignment.teacher',
            'teachingAssignment.subject',
            'teachingAssignment.schoolClass',
        ])
        ->latest()
        ->get();
    }

    private function hasTeacherConflict(
        $teacherId,
        $day,
        $start,
        $end,
        $ignoreId = null
    ) {
        return Schedule::when(
                $ignoreId,
                fn ($q) => $q->where('id', '!=', $ignoreId)
            )
            ->where('day', $day)
            ->whereHas(
                'teachingAssignment',
                fn ($q) => $q->where('teacher_id', $teacherId)
            )
            ->where(function ($q) use ($start, $end) {

                $q->where('start_time', '<', $end)
                  ->where('end_time', '>', $start);

            })
            ->exists();
    }

    private function hasClassConflict(
        $classId,
        $day,
        $start,
        $end,
        $ignoreId = null
    ) {
        return Schedule::when(
                $ignoreId,
                fn ($q) => $q->where('id', '!=', $ignoreId)
            )
            ->where('day', $day)
            ->whereHas(
                'teachingAssignment',
                fn ($q) => $q->where('class_id', $classId)
            )
            ->where(function ($q) use ($start, $end) {

                $q->where('start_time', '<', $end)
                  ->where('end_time', '>', $start);

            })
            ->exists();
    }

    public function store(
        ScheduleRequest $request
    ) {
        $assignment = TeachingAssignment::findOrFail(
            $request->teaching_assignment_id
        );

        if (
            $this->hasClassConflict(
                $assignment->class_id,
                $request->day,
                $request->start_time,
                $request->end_time
            )
        ) {
            return response()->json([
                'message' => 'Jadwal kelas Berhalangan'
            ], 422);
        }

        if (
            $this->hasTeacherConflict(
                $assignment->teacher_id,
                $request->day,
                $request->start_time,
                $request->end_time
            )
        ) {
            return response()->json([
                'message' => 'Guru memiliki jadwal pada jam tersebut'
            ], 422);
        }

        return Schedule::create(
            $request->validated()
        );
    }

    public function show(
        Schedule $schedule
    ) {
        return $schedule->load([
            'teachingAssignment.teacher',
            'teachingAssignment.subject',
            'teachingAssignment.schoolClass',
        ]);
    }

    public function update(
        ScheduleRequest $request,
        Schedule $schedule
    ) {
        $assignment = TeachingAssignment::findOrFail(
            $request->teaching_assignment_id
        );

        if (
            $this->hasClassConflict(
                $assignment->class_id,
                $request->day,
                $request->start_time,
                $request->end_time,
                $schedule->id
            )
        ) {
            return response()->json([
                'message' => 'Jadwal kelas berhalangan'
            ], 422);
        }

        if (
            $this->hasTeacherConflict(
                $assignment->teacher_id,
                $request->day,
                $request->start_time,
                $request->end_time,
                $schedule->id
            )
        ) {
            return response()->json([
                'message' => 'Guru memiliki jadwal pada jam tersebut'
            ], 422);
        }

        $schedule->update(
            $request->validated()
        );

        return $schedule->load([
            'teachingAssignment.teacher',
            'teachingAssignment.subject',
            'teachingAssignment.schoolClass',
        ]);
    }

    public function destroy(
        Schedule $schedule
    ) {
        $schedule->delete();

        return response()->json([
            'message' => 'Deleted'
        ]);
    }
}