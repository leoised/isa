<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    // Enroll a student in a course
    public function enroll($studentId, $courseId)
    {
        $student = Student::findOrFail($studentId);
        $course = Course::findOrFail($courseId);
        
        // syncWithoutDetaching prevents duplicate entries for the same student/course pair
        $student->courses()->syncWithoutDetaching([$course->id]);
        
        return response()->json(['message' => 'Enrolled successfully'], 201);
    }

    // Update Grade and Attendance on the pivot table
    public function updatePivot(Request $request, $studentId, $courseId)
    {
        $request->validate([
            'grade' => 'nullable|integer|min:0|max:100',
            'attendance_record' => 'nullable|string' // e.g. "AAXAA"
        ]);

        $student = Student::findOrFail($studentId);
        
        // Update the extra columns on the existing pivot row
        $student->courses()->updateExistingPivot($courseId, [
            'grade' => $request->grade,
            'attendance_record' => $request->attendance_record
        ]);

        return response()->json(['message' => 'Academic record updated']);
    }

    // Get specific enrollment details (grade/attendance) directly from pivot
    public function show($studentId, $courseId)
    {
        $record = DB::table('course_student')
                    ->where('student_id', $studentId)
                    ->where('course_id', $courseId)
                    ->first();

        if (!$record) {
            return response()->json(['message' => 'Not enrolled'], 404);
        }

        return response()->json($record);
    }

    // Helper: Get courses for a specific student
    public function getStudentCourses($studentId)
    {
        $student = Student::with('courses')->findOrFail($studentId);
        return response()->json($student->courses);
    }

    // Unenroll a student
    public function unenroll($studentId, $courseId)
    {
        $student = Student::findOrFail($studentId);
        $student->courses()->detach($courseId);
        return response()->noContent();
    }
}