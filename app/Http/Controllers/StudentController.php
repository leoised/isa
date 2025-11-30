<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with('courses');

        if ($request->has('search')) {
            $val = $request->search;
            $query->where(function($q) use ($val) {
                $q->where('name', 'like', "%{$val}%")
                  ->orWhere('email', 'like', "%{$val}%");
            });
        }

        if ($request->has('sort_by')) {
            $query->orderBy($request->sort_by, $request->input('sort_order', 'asc'));
        } else {
            $query->orderBy('id', 'desc');
        }

        return response()->json($query->paginate($request->input('per_page', 10)));
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

    public function show($id) { return Student::with('courses')->findOrFail($id); }

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