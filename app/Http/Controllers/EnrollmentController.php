<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    // Enroll a student
    public function enroll($studentId, $courseId)
    {
        $student = Student::findOrFail($studentId);
        $course = Course::findOrFail($courseId);
        
        $student->courses()->syncWithoutDetaching([$course->id]);
        
        return response()->json(['message' => 'Enrolled successfully'], 201);
    }

    // Unenroll a student
    public function unenroll($studentId, $courseId)
    {
        $student = Student::findOrFail($studentId);
        $student->courses()->detach($courseId);
        return response()->noContent();
    }

    // Update Grade and Attendance
    public function updatePivot(Request $request, $studentId, $courseId)
    {
        $student = Student::findOrFail($studentId);
        
        // Use updateExistingPivot to update extra columns on the pivot table
        $student->courses()->updateExistingPivot($courseId, [
            'grade' => $request->input('grade'),
            'attendance_record' => $request->input('attendance_record')
        ]);

        return response()->json(['message' => 'Academic record updated']);
    }

    // Get specific enrollment details (grade/attendance)
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

    // Get all courses for a student
    public function getStudentCourses($studentId)
    {
        $student = Student::with('courses')->findOrFail($studentId);
        return response()->json($student->courses);
    }

    // Get all students in a course
    public function getCourseStudents($courseId)
    {
        $course = Course::with('students')->findOrFail($courseId);
        return response()->json($course->students);
    }
}