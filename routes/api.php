<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;

// Standard Resources
Route::apiResource('students', StudentController::class);
Route::apiResource('courses', CourseController::class);

// Custom Enrollment Routes - specific routes BEFORE wildcard routes
Route::controller(EnrollmentController::class)->prefix('enrollments')->group(function () {
    // Specific routes first (to match before wildcard routes)
    Route::get('/student/{studentId}', 'getStudentCourses');
    Route::get('/course/{courseId}', 'getCourseStudents');
    
    // Then wildcard routes
    Route::post('/{studentId}/{courseId}', 'enroll');
    Route::delete('/{studentId}/{courseId}', 'unenroll');
    Route::get('/{studentId}/{courseId}', 'show');
    Route::put('/{studentId}/{courseId}', 'updatePivot');
});