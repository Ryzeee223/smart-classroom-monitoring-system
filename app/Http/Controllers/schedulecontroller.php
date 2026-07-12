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

        // Filter faculty by the logged-in user's college
        $collegeId = session('college_id');
        if (!$collegeId && session('user_id')) {
            $collegeId = \App\Models\User::query()
                ->where('id', session('user_id'))
                ->value('college_id');
        }

        $faculty_listQuery = \App\Models\User::whereIn('role', [2, 3, 4, 5])
            ->where('acc_status', 1);

        if ($collegeId) {
            $faculty_listQuery->where('college_id', $collegeId);
        }

        $faculty_list = $faculty_listQuery
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
            // Blade sends Day[] checkboxes -> request key will be Day
            'Day' => ['required', 'array', 'min:1'],
            'Day.*' => ['string'],

            // Blade uses Start_time/End_time
            'Start_time' => ['required'],
            'End_time' => ['required'],

            // Blade does NOT send Subject or course_id. It sends Course.
            'Course' => ['required'],

            'Room' => ['required'],
            'Semester' => ['required'],
            'School_year' => ['required'],

            // Blade uses year_level and section
            'year_level' => ['nullable'],
            'section' => ['nullable'],

            'user_id' => ['required', 'exists:users,id'],
        ]);

        // Convert checkbox array to a storable string (schedule.Day is varchar(20))
       

        $id = Str::uuid()->toString();
        Schedule::create([
            'id' => $id,
            'user_id' => $validatedData['user_id'],
            'year_level' => $validatedData['year_level'],
            'section' => $validatedData['section'],
            'Day' => $validatedData['Day'],
            'start_time' => $validatedData['Start_time'],
            'end_time' => $validatedData['End_time'],
            'Subject' => $validatedData['Course'],
            'Room' => $validatedData['Room'],
            'Semester' => $validatedData['Semester'],
            'School_year' => $validatedData['School_year'],


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
            'Day' => ['required'],
            'Start_time' => ['required'],
            'End_time' => ['required'],
            'Course' => ['required'],
            'Room' => ['required'],
            'Semester' => ['required'],
            'School_year' => ['required'],
            'year_level' => ['required'],
            'section' => ['required'],
        ]);

        $dayValue = is_array($validatedData['Day']) ? implode(', ', $validatedData['Day']) : (string) $validatedData['Day'];

        // schedule table columns are lowercase start_time/end_time
        $schedule->update([
            'Day' => $dayValue,
            'start_time' => $validatedData['Start_time'],
            'end_time' => $validatedData['End_time'],
            'Subject' => $validatedData['Course'],
            'Room' => $validatedData['Room'],
            'Semester' => $validatedData['Semester'],
            'School_year' => $validatedData['School_year'],
            'year_level' => $validatedData['year_level'],
            'section' => $validatedData['section'],
        ]);

        return redirect()->back()->with('success', 'Schedule updated successfully!');
    }

    // this is where the algorithm for checking schedule conflicts will be implemented
}


