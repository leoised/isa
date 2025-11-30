<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with('courses');

        // Search by name or email
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        // Sorting
        if ($request->has('sort_by')) {
            $sortOrder = $request->input('sort_order', 'asc');
            $query->orderBy($request->sort_by, $sortOrder);
        } else {
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

        return response()->json(Student::create($data), 201);
    }

    public function show($id)
    {
        return response()->json(Student::with('courses')->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $student->update($request->all());
        return response()->json($student);
    }

    public function destroy($id)
    {
        Student::findOrFail($id)->delete();
        return response()->noContent();
    }
}