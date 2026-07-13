<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\course;
use App\Models\Programs;
use App\Models\User;
use App\Models\room;
use App\Models\semyr;


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
            $collegeId = User::query()
                ->where('id', session('user_id'))
                ->value('college_id');
        }

        $faculty_listQuery = User::whereIn('role', [2, 3, 4, 5])
            ->where('acc_status', 1);

        if ($collegeId) {
            $faculty_listQuery->where('college_id', $collegeId);
        }

        $faculty_list = $faculty_listQuery
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        $schoolYears = semyr::query()
            ->orderBy('id')
            ->get()
            ->pluck('school_year')
            ->unique()
            ->values();

        if ((int) session('user_role') === 5) {
            $user_id = session('user_id');
            $schedules = Schedule::where('user_id', $user_id)->get();
        } else {
            $schedules = Schedule::all();
        }


        $programs = Programs::all();
        $courses = course::all();

        // View expects $course for the course dropdown
        $course = $courses;

        $rooms = room::all();

        return view('schedules', compact('schedules', 'faculty_list', 'course', 'schoolYears', 'programs', 'rooms'));
    }

    public function store(Request $request)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $validatedData = $request->validate([
            'Day' => ['required', 'array', 'min:1'],
            'Day.*' => ['string'],

            
            'program_id' => ['required', 'exists:Programs,id'],
            'Course' => ['required', 'exists:courses,id'],
            'Room' => ['required', 'exists:room,id'],
            'user_id' => ['required', 'exists:users,id'],
            'Semester' => ['required', 'exists:semyr,id'],
            'School_year' => ['required', 'exists:semyr,id'],

            'Start_time' => ['required'],
            'End_time' => ['required'],

            'year_level' => ['required', 'string'],
            'section' => ['required', 'string'],
        ]);

        $dayValue = implode(', ', $validatedData['Day']);

        $courseId = course::query()
            ->where('course_name', $validatedData['Course'])
            ->value('id');
        if (!$courseId) {
            return redirect()->back()->with('error', 'Selected course not found.');
        }

        $roomId = room::query()
            ->where('room_name', $validatedData['Room'])
            ->value('id');
        if (!$roomId) {
            return redirect()->back()->with('error', 'Selected room not found.');
        }

        Schedule::create([
            'user_id' => $validatedData['user_id'],
            'program_id' => $validatedData['program_id'],
            'course_id' => $courseId,
            'room_id' => $roomId,

            'year_level' => $validatedData['year_level'],
            'section' => $validatedData['section'],

            'day' => $dayValue,
            'start_time' => $validatedData['Start_time'],
            'end_time' => $validatedData['End_time'],

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
            'Day' => ['required', 'array', 'min:1'],

            'user_id'    => ['required', 'exists:users,id'],
            'program_id' => ['required', 'exists:programs,id'],
            'Course_id'  => ['required', 'exists:courses,id'],
            'Room_id'    => ['required', 'exists:room,id'],
            
            'Start_time' => ['required'],
            'End_time'   => ['required'],

            'Semester'   => ['required', 'exists:semyr,id'],
            'School_year'=> ['required', 'exists:semyr,id'],

            'year_level' => ['required', 'string'],
            'section'    => ['required', 'string'],
        ]);

        $dayValue = implode(', ', $validatedData['Day']);

        $courseId = course::query()
            ->where('course_name', $validatedData['Course'])
            ->value('id');
        if (!$courseId) {
            return redirect()->back()->with('error', 'Selected course not found.');
        }

        $roomId = room::query()
            ->where('room_name', $validatedData['Room'])
            ->value('id');
        if (!$roomId) {
            return redirect()->back()->with('error', 'Selected room not found.');
        }

        $schedule->update([
            'program_id' => $validatedData['program_id'],
            'course_id' => $courseId,
            'room_id' => $roomId,

            'year_level' => $validatedData['year_level'],
            'section' => $validatedData['section'],

            'day' => $dayValue,
            'start_time' => $validatedData['Start_time'],
            'end_time' => $validatedData['End_time'],

            'Semester' => $validatedData['Semester'],
            'School_year' => $validatedData['School_year'],
        ]);


        return redirect()->back()->with('success', 'Schedule updated successfully!');
    }

    // this is where the algorithm for checking schedule conflicts will be implemented
}


