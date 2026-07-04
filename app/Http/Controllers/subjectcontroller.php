<?php

namespace App\Http\Controllers;

use App\Models\course;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Programs;

class subjectcontroller extends Controller
{
    public function index()
    {
$courses = course::with('course')->get();
        $Programs = Programs::all();
        return view('subjects', compact('courses', 'Programs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_code' => 'required|unique:subjects',
            'subject_name' => 'required',
            'description' => 'nullable',
            'course_id' => 'required|exists:courses,id'
            
        ]);

        course::create($request->all());

        return redirect()->route('subjects')->with('success', 'Course created successfully.');
    }

    public function edit($id)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }
        $Programs = Programs::all();
        $course = course::findOrFail($id);
        return view('course.edit', compact('course', 'Programs'));
    }

    public function update(Request $request, $id)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $request->validate([
            'course_code' => 'required|string|max:255|unique:subjects,subject_code,' . $id,
            'course_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'required|exists:Programs,id',
        ]);

        $course = course::findOrFail($id);
        $course->update([
            'course_code' => $request->course_code,
            'course_name' => $request->course_name,
            'description' => $request->description ?? '',
            'course_id' => $request->course_id,
        ]);

        return redirect()->route('subjects')->with('success', 'Course updated successfully!');
    }

    public function destroy($id)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        course::findOrFail($id)->delete();

        return back()->with('success', 'Course deleted successfully!');
    }
}

