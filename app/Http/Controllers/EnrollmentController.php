<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    public function enroll($studentId, $courseId)
    {
        $student = Student::findOrFail($studentId);
        $course = Course::findOrFail($courseId);
        
        // Prevent duplicate enrollment
        $student->courses()->syncWithoutDetaching([$course->id]);
        
        return response()->json(['message' => 'Enrolled successfully'], 201);
    }

    public function unenroll($studentId, $courseId)
    {
        $student = Student::findOrFail($studentId);
        $student->courses()->detach($courseId);
        return response()->noContent();
    }

    public function updatePivot(Request $request, $studentId, $courseId)
    {
        $student = Student::findOrFail($studentId);
        
        // Update grade and attendance
        $student->courses()->updateExistingPivot($courseId, [
            'grade' => $request->grade,
            'attendance_record' => $request->attendance_record
        ]);
        return response()->json(['message' => 'Record updated']);
    }

    public function show($studentId, $courseId)
    {
        $record = DB::table('course_student')
            ->where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->first();
            
        if (!$record) return response()->json(['error' => 'Not found'], 404);
        
        return response()->json($record);
    }

    public function getStudentCourses($studentId) {
        return Student::with('courses')->findOrFail($studentId)->courses;
    }

    public function getCourseStudents($courseId) {
        return Course::with('students')->findOrFail($courseId)->students;
    }
}