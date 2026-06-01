<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use App\Models\Schedule;
use App\Models\User;
use App\Models\Subject;

class schedulecontroller extends Controller
{
    public function index()
    {
        if (!session('logged_in')) {
            return redirect('/');
        }
        $faculty_list = \App\Models\users::where('role', 4)->where('acc_status', 1)->get();
        $subjects = \App\Models\Subject::all();
        $courses = \App\Models\Course::all();
        if (session('user_role') == 5) {
            $user_id = session('user_id');
            $schedules = Schedule::where('user_id', $user_id)->get();
        } else {
            $schedules = Schedule::all();
        }
        return view('schedules', compact('schedules', 'faculty_list', 'subjects', 'courses'));
    }

    public function store(Request $request)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $validatedData = $request->validate([
            'Day' => 'required',
            'Time' => 'required',
            'Subject' => 'required',
            'Room' => 'required',
            'Semester' => 'required',
            'School_year' => 'required',
            'course' => 'nullable',
            'year_level' => 'nullable',
'section' => 'nullable',
            'user_id' => 'required|exists:users,id',
        ]);

        $id = Str::uuid()->toString();
        Schedule::create([
            'id' => $id,
            'user_id' => $validatedData['user_id'],
            'Day' => $validatedData['Day'],
            'Time' => $validatedData['Time'],
            'Subject' => $validatedData['Subject'],
            'Room' => $validatedData['Room'],
            'Semester' => $validatedData['Semester'],
            'School_year' => $validatedData['School_year'],
            'course' => $request->course,
            'year_level' => $request->year_level,
            'section' => $request->section,
        ]);

        return redirect()->back()->with('success', 'Schedule added successfully!');
    }

    public function destroy($id)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $schedule = Schedule::find($id);
        if (!$schedule) {
            return redirect()->back()->with('error', 'Schedule not found.');
        }
        $schedule->delete();

        return redirect()->back()->with('success', 'Schedule deleted successfully!');
    }

    public function update(Request $request, $id)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $schedule = Schedule::findOrFail($id);

        $validatedData = $request->validate([
            'Day' => 'required',
            'Time' => 'required',
            'Subject' => 'required',
            'Room' => 'required',
            'Semester' => 'required',
            'School_year' => 'required',
            'course' => 'nullable',
            'year_level' => 'nullable',
            'section' => 'nullable',
        ]);

        $schedule->update($validatedData);

        return redirect()->back()->with('success', 'Schedule updated successfully!');
    }
}

