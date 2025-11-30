<?php


namespace App\Http\Controllers;


use App\Models\Course;
use Illuminate\Http\Request;


class CourseController extends Controller
{
public function index()
{
return Course::with('students')->get();
}


public function store(Request $r)
{
$data = $r->validate([
'title' => 'required|string|max:255',
'description' => 'nullable|string',
'credits' => 'nullable|integer|min:1|max:10',
]);
return Course::create($data);
}


public function show($id)
{
return Course::with('students')->findOrFail($id);
}


public function update(Request $r, $id)
{
$course = Course::findOrFail($id);
$data = $r->validate([
'title' => 'sometimes|required|string|max:255',
'description' => 'nullable|string',
'credits' => 'nullable|integer|min:1|max:10',
]);
$course->update($data);
return $course;
}


public function destroy($id)
{
Course::findOrFail($id)->delete();
return response()->json(['message' => 'deleted']);
}
}