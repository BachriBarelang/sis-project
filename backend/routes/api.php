<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\SchoolClassController;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\TeachingAssignmentController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\DashboardController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register-admin', [AuthController::class, 'registerAdmin']);


Route::middleware('auth:api')->group(function () {

    Route::get('/profile', [AuthController::class, 'profile']);

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/dashboard',[DashboardController::class, 'index']);

    Route::apiResource('students', StudentController::class);

    Route::apiResource('teachers', TeacherController::class);

    Route::apiResource('classes',  SchoolClassController::class);

    Route::get('classes/{id}/detail',[SchoolClassController::class, 'detail']);

    Route::apiResource('subjects',SubjectController::class);

    Route::apiResource('teaching-assignments',TeachingAssignmentController::class);

    Route::apiResource('schedules',ScheduleController::class);

    Route::post('classes/{id}/students',[SchoolClassController::class, 'assignStudents'] );

    Route::delete('classes/{id}/students/{studentId}',[SchoolClassController::class, 'removeStudent'] );

});