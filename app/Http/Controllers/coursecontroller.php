<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\course;

class CourseController extends Controller
{
    public function index()
    {
        $sessionRole = (int) (session('user_role') ?? 0);
        $currentUser = \App\Models\User::find(session('user_id'));
        $currentCollegeId = (int) ($currentUser?->college_id ?? 0);

        $query = course::query();
        if (in_array($sessionRole, [2, 3], true) && $currentCollegeId) {
            $query->where('college_id', $currentCollegeId);
        }

        $course = $query->get();

        return view('course', compact('course'));
    }

    public function store(Request $request)
    {
        $sessionRole = (int) (session('user_role') ?? 0);
        $currentUser = \App\Models\User::find(session('user_id'));

        if (!in_array($sessionRole, [1, 2, 3], true)) {
            abort(403);
        }

        $request->validate([
            'college_id' => 'required|integer|exists:college,id',
            'course_code' => 'required|string|max:100|unique:courses,course_code',
            'course_name' => 'required|string|max:150',
            'description' => 'nullable|string',
        ]);

        $collegeId = (int) $request->input('college_id');

        // Prevent forging: dean/assistant dean can only store inside their assigned college
        if (in_array($sessionRole, [2, 3], true)) {
            $collegeId = (int) ($currentUser?->college_id ?? 0);
            if (!$collegeId) {
                abort(403, 'No assigned college for your account.');
            }
        }

        course::create([
            'college_id' => $collegeId,
            'course_code' => $request->course_code,
            'course_name' => $request->course_name,
            'description' => $request->description ?? '',
        ]);

        return redirect()->route('course')->with('success', 'Course created successfully.');
    }

    public function edit($id)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }
        $course = course::findOrFail($id);
        return view('courses.edit', compact('course'));
    }

    public function update(Request $request, $id)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $request->validate([
            'college_id' => 'required|integer|exists:college,id',
            'course_code' => 'required|string|max:100|unique:courses,course_code,' . $id,
            'course_name' => 'required|string|max:150',
            'description' => 'nullable|string',
        ]);

        $course = course::findOrFail($id);
        // Prevent forging: keep college_id as the logged-in user's assigned college for dean/assistant dean
        $collegeId = (int) $request->input('college_id');
        $sessionRole = (int) (session('user_role') ?? 0);
        $currentUser = \App\Models\User::find(session('user_id'));
        if (in_array($sessionRole, [2, 3], true)) {
            $collegeId = (int) ($currentUser?->college_id ?? 0);
            if (!$collegeId) {
                abort(403, 'No assigned college for your account.');
            }
        }

        $course->update([
            'college_id' => $collegeId,
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

        course::findOrFail($id)->delete();

        return back()->with('success', 'Course deleted successfully!');
    }
}
