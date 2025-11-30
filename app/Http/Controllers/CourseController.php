<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of courses with Pagination.
     */
    public function index()
    {
        // Simple pagination for courses, 10 per page
        return response()->json(Course::with('students')->paginate(10));
    }

    /**
     * Store a newly created course.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'credits' => 'nullable|integer|min:1|max:10',
        ]);

        $course = Course::create($data);
        return response()->json($course, 201);
    }

    /**
     * Display the specified course.
     */
    public function show($id)
    {
        return response()->json(Course::with('students')->findOrFail($id));
    }

    /**
     * Update the specified course.
     */
    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        
        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'credits' => 'nullable|integer|min:1|max:10',
        ]);

        $course->update($data);
        return response()->json($course);
    }

    /**
     * Remove the specified course.
     */
    public function destroy($id)
    {
        Course::findOrFail($id)->delete();
        return response()->noContent();
    }
}