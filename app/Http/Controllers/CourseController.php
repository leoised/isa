<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with('students');

        if ($request->has('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        if ($request->has('sort_by')) {
            $query->orderBy($request->sort_by, $request->input('sort_order', 'asc'));
        } else {
            $query->orderBy('id', 'desc');
        }

        return response()->json($query->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'credits' => 'nullable|integer|min:1|max:10',
        ]);
        return response()->json(Course::create($data), 201);
    }

    public function show($id) { return Course::with('students')->findOrFail($id); }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        $course->update($request->all());
        return response()->json($course);
    }

    public function destroy($id)
    {
        Course::findOrFail($id)->delete();
        return response()->noContent();
    }
}