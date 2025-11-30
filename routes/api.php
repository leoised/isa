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

Route::get('/test', function () {
    return response()->json(['message' => 'API working']);
});

// Standard Resources with Search/Sort support
Route::apiResource('students', StudentController::class);
Route::apiResource('courses', CourseController::class);

// FIXED: Custom Enrollment Routes to match your Controller
Route::controller(EnrollmentController::class)->prefix('enrollments')->group(function () {
    // Enroll a student (POST /api/enrollments/{student}/{course})
    Route::post('/{studentId}/{courseId}', 'enroll');
    
    // Unenroll a student (DELETE /api/enrollments/{student}/{course})
    Route::delete('/{studentId}/{courseId}', 'unenroll');
    
    // Get details (Grade/Attendance)
    Route::get('/{studentId}/{courseId}', 'show');
    
    // Update Grade or Attendance
    Route::put('/{studentId}/{courseId}', 'updatePivot');

    // Helper routes for the GUI
    Route::get('/student/{studentId}', 'getStudentCourses');
    Route::get('/course/{courseId}', 'getCourseStudents');
});