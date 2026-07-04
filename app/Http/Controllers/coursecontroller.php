<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\course;
use App\Models\Programs;
use App\Models\college;

class coursecontroller extends Controller
{
    public function index()
    {
        $course = course::with('Programs')->get();
        $Programs = Programs::all();
        return view('course', compact('course', 'Programs'));

        $college = college::all();
        return view('course', compact('course', 'Programs', 'college'));
    }

    public function store(Request $request)
    {
        $request->validate([
            
            'course_code' => 'required|unique:course',
            'course_name' => 'required',
            'description' => 'nullable',
            'program_id' => 'required|exists:programs,id'
            
        ]);

        course::create($request->all());

        return redirect()->route('course')->with('success', 'Course created successfully.');
    }

    public function edit($id)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }
        $Programs = Programs::all();
        $course = course::findOrFail($id);
        return view('courses.edit', compact('course', 'Programs'));
    }

    public function update(Request $request, $id)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $request->validate([
            'course_code' => 'required|string|max:255|unique:course,course_code,' . $id,
            'course_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'program_id' => 'required|exists:programs,id',
        ]);

        $course = course::findOrFail($id);
        $course->update([
            'course_code' => $request->course_code,
            'course_name' => $request->course_name,
            'description' => $request->description ?? '',
            'program_id' => $request->program_id,
        ]);

        return redirect()->route('course')->with('success', 'Course updated successfully!');
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

