<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource with Search and Pagination.
     */
    public function index(Request $request)
    {
        $query = Student::with('courses');

        // Feature: Search
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        // Feature: Pagination (returns 10 per page)
        return response()->json($query->paginate(10));
    }

    /**
     * Store a newly created student.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'age' => 'nullable|integer|min:16|max:100',
        ]);

        $student = Student::create($data);
        return response()->json($student, 201);
    }

    /**
     * Display the specified student.
     */
    public function show($id)
    {
        return response()->json(Student::with('courses')->findOrFail($id));
    }

    /**
     * Update the specified student.
     */
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:students,email,' . $id,
            'age' => 'nullable|integer|min:16|max:100',
        ]);

        $student->update($data);
        return response()->json($student);
    }

    /**
     * Remove the specified student.
     */
    public function destroy($id)
    {
        Student::findOrFail($id)->delete();
        return response()->noContent();
    }
}