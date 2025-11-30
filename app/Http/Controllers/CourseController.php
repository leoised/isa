<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with('students');

        // Search (Optional feature for Courses too)
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where('title', 'like', "%{$searchTerm}%");
        }

        // Sorting
        if ($request->has('sort_by')) {
            $sortBy = $request->sort_by;
            $sortOrder = $request->input('sort_order', 'asc');
            
            if (in_array($sortBy, ['id', 'title', 'credits', 'created_at'])) {
                $query->orderBy($sortBy, $sortOrder);
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        return response()->json($query->paginate(10));
    }

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

    public function show($id)
    {
        return response()->json(Course::with('students')->findOrFail($id));
    }

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

    public function destroy($id)
    {
        Course::findOrFail($id)->delete();
        return response()->noContent();
    }
}