<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Schedule;
use App\Models\course;
use App\Models\programs;
use App\Models\User;

class schedulecontroller extends Controller
{
    public function index()
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $faculty_list = \App\Models\User::whereIn('role', [2, 3, 4, 5])
            ->where('acc_status', 1)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();
    
    
   
       $schoolYears = \App\Models\semyr::query()
            ->orderBy('id')
            ->get()
            ->pluck('school_year')
            ->unique()
            ->values(); 

        if (session('user_role') == 5) {
            $user_id = session('user_id');
            $schedules = Schedule::where('user_id', $user_id)->get();
        } else {
            $schedules = Schedule::all();
            $course = course::all();
        }

        return view('schedules', compact('schedules', 'faculty_list', 'course', 'schoolYears'));
    
    
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
            'Programs' => 'required',
            'course' => 'required',
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
            'Programs' => $validatedData['Programs'],
            'year_level' => $validatedData['year_level'],
            'section' => $validatedData['section'],
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
            'Programs' => 'required',
            'year_level' => 'required',
            'section' => 'required',
        ]);

        $schedule->update($validatedData);

        return redirect()->back()->with('success', 'Schedule updated successfully!');
    }
}


