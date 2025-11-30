<?php


namespace App\Http\Controllers;


use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;


class EnrollmentController extends Controller
{
public function enroll($studentId, $courseId)
{
$student = Student::findOrFail($studentId);
$course = Course::findOrFail($courseId);
$student->courses()->syncWithoutDetaching([$course->id]);
return response()->json(['message' => 'enrolled']);
}


public function getStudentCourses($studentId)
{
return Student::with('courses')->findOrFail($studentId)->courses;
}


public function getCourseStudents($courseId)
{
return Course::with('students')->findOrFail($courseId)->students;
}


public function unenroll($studentId, $courseId)
{
$student = Student::findOrFail($studentId);
$student->courses()->detach($courseId);
return response()->json(['message' => 'unenrolled']);
}
}