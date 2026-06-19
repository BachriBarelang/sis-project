<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Schedule;
use App\Models\TeachingAssignment;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'students' => Student::count(),
            'teachers' => Teacher::count(),
            'classes' => SchoolClass::count(),
            'subjects' => Subject::count(),
        ];

        $classes = SchoolClass::with([
            'homeroomTeacher'
        ])
        ->withCount('students')
        ->orderBy('level')
        ->orderBy('name')
        ->get();

        $distribution = SchoolClass::withCount('students')
            ->get()
            ->groupBy('level')
            ->map(function ($classes, $level) {
                return [
                    'level' => $level,
                    'total_classes' => $classes->count(),
                    'total_students' => $classes->sum('students_count'),
                ];
            })
            ->values();

        $today = Carbon::now()->locale('id');

        $dayName = match ($today->englishDayOfWeek) {
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        };

        $todaySchedules = Schedule::with([
            'teachingAssignment.teacher',
            'teachingAssignment.subject',
            'teachingAssignment.schoolClass',
        ])
        ->where('day', $dayName)
        ->orderBy('start_time')
        ->get();

        $activities = collect();
        Student::latest()
            ->take(5)
            ->get()
            ->each(function ($student) use ($activities) {
                $activities->push([
                    'type' => 'student',
                    'icon' => 'mdi-account',
                    'message' => "Siswa {$student->name} ditambahkan",
                    'created_at' => $student->created_at,
                ]);
            });

        Teacher::latest()
            ->take(5)
            ->get()
            ->each(function ($teacher) use ($activities) {
                $activities->push([
                    'type' => 'teacher',
                    'icon' => 'mdi-school',
                    'message' => "Guru {$teacher->name} ditambahkan",
                    'created_at' => $teacher->created_at,
                ]);
            });

        SchoolClass::latest()
            ->take(5)
            ->get()
            ->each(function ($class) use ($activities) {
                $activities->push([
                    'type' => 'class',
                    'icon' => 'mdi-google-classroom',
                    'message' => "Kelas {$class->name} dibuat",
                    'created_at' => $class->created_at,
                ]);
            });

        Subject::latest()
            ->take(5)
            ->get()
            ->each(function ($subject) use ($activities) {
                $activities->push([
                    'type' => 'subject',
                    'icon' => 'mdi-book-open-page-variant',
                    'message' => "Mata pelajaran {$subject->name} ditambahkan",
                    'created_at' => $subject->created_at,
                ]);
            });

        $activities = $activities
            ->sortByDesc('created_at')
            ->take(10)
            ->values();
        
        $warnings = [];

        $studentsWithoutClass =
            Student::whereNull('class_id')->count();

        if ($studentsWithoutClass > 0) {
            $warnings[] = [
                'type' => 'error',
                'message' =>
                    "{$studentsWithoutClass} siswa belum masuk kelas",
            ];
        }

        $classesWithoutTeacher =
            SchoolClass::whereNull(
                'homeroom_teacher_id'
            )->count();

        if ($classesWithoutTeacher > 0) {
            $warnings[] = [
                'type' => 'warning',
                'message' =>
                    "{$classesWithoutTeacher} kelas belum memiliki wali kelas",
            ];
        }

        $emptyClasses =
            SchoolClass::doesntHave(
                'students'
            )->count();

        if ($emptyClasses > 0) {
            $warnings[] = [
                'type' => 'warning',
                'message' =>
                    "{$emptyClasses} kelas tidak memiliki siswa",
            ];
        }

        $assignmentWithoutSchedule =
            TeachingAssignment::doesntHave(
                'schedules'
            )->count();

        if ($assignmentWithoutSchedule > 0) {
            $warnings[] = [
                'type' => 'info',
                'message' =>
                    "{$assignmentWithoutSchedule} pengampu belum memiliki jadwal",
            ];
        }

        return response()->json([
            'stats' => $stats,
            'classes' => $classes,
            'distribution' => $distribution,
            'today_schedules' => $todaySchedules,
            'today' => $dayName,
            'activities' => $activities,
            'warnings' => $warnings,
        ]);
    }
}