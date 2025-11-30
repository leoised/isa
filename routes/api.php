<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Standard Resources (Now with Search/Pagination built-in)
Route::apiResource('students', StudentController::class);
Route::apiResource('courses', CourseController::class);

// Advanced Enrollment Routes
Route::controller(EnrollmentController::class)->prefix('enrollments')->group(function () {
    // Enroll a student
    Route::post('/{studentId}/{courseId}', 'enroll');
    
    // Unenroll a student
    Route::delete('/{studentId}/{courseId}', 'unenroll');
    
    // Get details (Grade/Attendance) for a specific enrollment
    Route::get('/{studentId}/{courseId}', 'show');
    
    // Update Grade or Attendance
    Route::put('/{studentId}/{courseId}', 'updatePivot');

    // Get all courses for a student (Helper)
    Route::get('/student/{studentId}', 'getStudentCourses');
});