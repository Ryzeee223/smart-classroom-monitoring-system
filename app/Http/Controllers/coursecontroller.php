<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Subject;

class CourseController extends Controller
{
    public function index()
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $courses = Course::all();
        $subjects = Subject::all();
        return view('course', compact('courses', 'subjects'));
    }

    public function store(Request $request)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $request->validate([
            'course_code' => 'required|string|max:255|unique:courses,course_code',
            'course_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Course::create([
            'course_code' => $request->course_code,
            'course_name' => $request->course_name,
            'description' => $request->description ?? '',
        ]);

        return back()->with('success', 'Course saved successfully!');
    }

    public function edit($id)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $course = Course::findOrFail($id);
        return view('courses.edit', compact('course'));
    }

    public function update(Request $request, $id)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $course = Course::findOrFail($id);

        $request->validate([
            'course_code' => 'required|string|max:255|unique:courses,course_code,' . $course->id,
            'course_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $course->update([
            'course_code' => $request->course_code,
            'course_name' => $request->course_name,
            'description' => $request->description ?? '',
        ]);

        return redirect()->route('course')->with('success', 'Course updated successfully!');
    }

    public function destroy($id)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $course = Course::findOrFail($id);
        $course->delete();

        return redirect()->route('course')->with('success', 'Course deleted successfully!');
    }
}

