<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with('courses');

        // Search
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        // Sorting
        if ($request->has('sort_by')) {
            $sortBy = $request->sort_by;
            $sortOrder = $request->input('sort_order', 'asc'); // Default to ascending
            
            // Allow sorting by specific columns only
            if (in_array($sortBy, ['id', 'name', 'email', 'age', 'created_at'])) {
                $query->orderBy($sortBy, $sortOrder);
            }
        } else {
            // Default sort
            $query->orderBy('id', 'desc');
        }

        return response()->json($query->paginate(10));
    }

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

    public function show($id)
    {
        return response()->json(Student::with('courses')->findOrFail($id));
    }

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

    public function destroy($id)
    {
        Student::findOrFail($id)->delete();
        return response()->noContent();
    }
}