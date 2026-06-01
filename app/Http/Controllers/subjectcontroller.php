<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Course;

class subjectcontroller extends Controller
{
    public function index()
    {
$subjects = Subject::with('course')->get();
        $courses = Course::all();
        return view('subjects', compact('subjects', 'courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_code' => 'required|unique:subjects',
            'subject_name' => 'required',
            'description' => 'nullable',
            'course_id' => 'required|exists:courses,id'
            
        ]);

        Subject::create($request->all());

        return redirect()->route('subjects')->with('success', 'Subject created successfully.');
    }

    public function edit($id)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }
        $courses = Course::all();
        $subject = Subject::findOrFail($id);
        return view('subjects.edit', compact('subject', 'courses'));
    }

    public function update(Request $request, $id)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $request->validate([
            'subject_code' => 'required|string|max:255|unique:subjects,subject_code,' . $id,
            'subject_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
        ]);

        $subject = Subject::findOrFail($id);
        $subject->update([
            'subject_code' => $request->subject_code,
            'subject_name' => $request->subject_name,
            'description' => $request->description ?? '',
            'course_id' => $request->course_id,
        ]);

        return redirect()->route('subjects')->with('success', 'Subject updated successfully!');
    }

    public function destroy($id)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        Subject::findOrFail($id)->delete();

        return back()->with('success', 'Subject deleted successfully!');
    }
}

