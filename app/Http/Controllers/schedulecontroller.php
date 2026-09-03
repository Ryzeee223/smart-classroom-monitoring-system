<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Course;
use App\Models\Programs;
use App\Models\User;
use App\Models\room;
use App\Models\semyr;
use App\Models\College;


class schedulecontroller extends Controller
{
    public function index()
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $sessionRole = (int) (session('user_role') ?? 0);

        // Resolve the logged-in user's college
        $collegeId = session('college_id');
        if (!$collegeId && session('user_id')) {
            $collegeId = User::query()
                ->where('id', session('user_id'))
                ->value('college_id');
        }

        // Faculty list: everyone who can be assigned a schedule
        // (Dean=2, Asst. Dean=3, Faculty=4, Program Head=5).
        // Non-admin users only see faculty from their own college,
        // so the Dean / Assistant Dean's dropdown matches their college.
        $faculty_listQuery = User::whereIn('role', [2, 3, 4, 5]);

        if ($sessionRole !== 1 && $collegeId) {
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

        // Schedules: Program Heads only see their own schedule;
        // Dean / Assistant Dean see schedules of users in their college;
        // Admin sees all.
        if ($sessionRole === 5) {
            $schedules = Schedule::where('user_id', session('user_id'))->get();
        } elseif ($sessionRole !== 1 && $collegeId) {
            $collegeUserIds = User::where('college_id', $collegeId)->pluck('id');
            $schedules = Schedule::whereIn('user_id', $collegeUserIds)->get();
        } else {
            $schedules = Schedule::all();
        }

        $programs = Programs::all();
        $courses = Course::all();
        $rooms = room::all();

        // Current college name for display (used by Dean / Assistant Dean)
        $collegeName = $collegeId ? optional(College::find($collegeId))->college_name : null;

        // Optional: preselect a faculty when arriving from a request approval (e.g. Summer class "set schedule")
        $selectedFacultyId = (int) request('user_id', 0);

        return view('schedules', compact('schedules', 'faculty_list', 'courses', 'schoolYears', 'programs', 'rooms', 'selectedFacultyId', 'collegeName'));
    }

    public function store(Request $request)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $validatedData = $request->validate([
            'Day' => ['required', 'array', 'min:1'],
            'Day.*' => ['string'],

            'program_id' => ['required', 'exists:programs,id'],
            'Course' => ['required', 'exists:courses,id'],
            'Room' => ['required', 'exists:room,id'],
            'user_id' => ['required', 'exists:users,id'],

            'Semester' => ['required', 'string'],
            'School_year' => ['required', 'string'],

            'Start_time' => ['required'],
            'End_time' => ['required'],

            'year_level' => ['required', 'string', 'max:50'],
            'section' => ['required', 'string', 'max:1'],
        ]);

        $days = array_values(array_unique((array) $validatedData['Day']));

        // 1. Conflict Check Loop
        foreach ($days as $singleDay) {
            $roomConflict = Schedule::where('room_id', $validatedData['Room'])
                ->where('day', $singleDay)
                ->where('School_year', $validatedData['School_year'])
                ->where('Semester', $validatedData['Semester'])
                ->where(function ($query) use ($validatedData) {
                    $query->where('start_time', '<', $validatedData['End_time'])
                          ->where('end_time', '>', $validatedData['Start_time']);
                })
                ->exists();

            if ($roomConflict) {
                return back()->withErrors([
                    'conflict' => "Schedule conflict: Room is already occupied on {$singleDay}."
                ])->withInput();
            }
        }

        // 2. Insert Loop (Creates 1 row per selected day)
        foreach ($days as $singleDay) {
            Schedule::create([
                'user_id'     => $validatedData['user_id'],
                'program_id'  => $validatedData['program_id'],
                'course_id'   => $validatedData['Course'],
                'room_id'     => $validatedData['Room'],
                'year_level'  => $validatedData['year_level'],
                'section'     => $validatedData['section'],
                'day'         => $singleDay, // Saves 'Mon', 'Tue', etc.
                'start_time'  => $validatedData['Start_time'],
                'end_time'    => $validatedData['End_time'],
                'Semester'    => $validatedData['Semester'],
                'School_year' => $validatedData['School_year'],
            ]);
        }
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
            'Day'        => ['required', 'array', 'min:1'],
            'Day.*'      => ['string', 'in:Mon,Tue,Wed,Thu,Fri,Sat,Sun'],

            'program_id' => ['required', 'exists:programs,id'],
            'Course'     => ['required', 'exists:courses,id'],
            'Room'       => ['required', 'exists:room,id'],

            'Start_time' => ['required'],
            'End_time'   => ['required'],

            'Semester'    => ['required', 'string'],
            'School_year' => ['required', 'string'],

            'year_level' => ['required', 'string', 'max:50'],
            'section'    => ['required', 'string', 'max:1'],
        ]);

        $days = array_values(array_unique((array) $validatedData['Day']));

        // Blade submits Course and Room as IDs.
        $courseId = $validatedData['Course'];
        $roomId = $validatedData['Room'];

        // A schedule row can only hold one day in this schema, so each checked day becomes its own record.
        $schedule->delete();

        foreach ($days as $singleDay) {
            Schedule::create([
                'user_id' => $schedule->user_id,
                'program_id' => $validatedData['program_id'],
                'course_id' => $courseId,
                'room_id' => $roomId,
                'year_level' => $validatedData['year_level'],
                'section' => $validatedData['section'],
                'day' => $singleDay,
                'start_time' => $validatedData['Start_time'],
                'end_time' => $validatedData['End_time'],
                'Semester' => $validatedData['Semester'],
                'School_year' => $validatedData['School_year'],
            ]);
        }

        return redirect()->back()->with('success', 'Schedule updated successfully!');
    }

  
    // this is where the algorithm for checking schedule conflicts will be implemented
    // Receives: day, room_id, start_time, end_time
    // Returns JSON with conflict=true/false
    public function bookingsystem(Request $request)
    {
        if (!session('logged_in')) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $validated = $request->validate([
            'day' => ['required', 'string'],
            'room_id' => ['required', 'integer', 'exists:room,id'],
            'start_time' => ['required', 'date_format:H:i:s'],
            'end_time' => ['required', 'date_format:H:i:s', 'after:start_time'],
        ]);

        // conflict rule: same room + same day + overlapping time interval
        // overlap condition: existing.start < new.end AND existing.end > new.start
        $hasConflict = Schedule::query()
            ->where('room_id', $validated['room_id'])
            ->where('day', $validated['day'])
            ->where(function ($q) use ($validated) {
                $q->where('start_time', '<', $validated['end_time'])
                  ->where('end_time', '>', $validated['start_time']);
            })
            ->exists();

        return response()->json([
            'conflict' => $hasConflict,
        ]);
    }
}
