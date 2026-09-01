<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\semyr;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\report;

class ReportController extends Controller
{
    protected array $dayMap = [
        'Mon' => 1,
        'Tue' => 2,
        'Wed' => 3,
        'Thu' => 4,
        'Fri' => 5,
        'Sat' => 6,
        'Sun' => 7,
    ];
public function index()
{
    $now = Carbon::now();
    $todayDay = $now->format('D');
    $todayFullDay = $now->format('l');

    $matchingDays = array_values(array_unique([
        $todayDay,
        $todayFullDay,
        strtolower($todayDay),
        strtolower($todayFullDay),
        ucfirst(strtolower($todayDay)),
        ucfirst(strtolower($todayFullDay)),
    ]));

    $todayLetter = match($todayDay) {
        'Mon' => 'M',
        'Tue' => 'T',
        'Wed' => 'W',
        'Thu' => 'TH',
        'Fri' => 'F',
        'Sat' => 'S',
        'Sun' => 'SU',
        default => '',
    };

    $semesterRecord = semyr::latest('id')->first();
    $currentSemester = $semesterRecord?->semester ?? 'Current Semester';
    $currentSchoolYear = $semesterRecord?->school_year ?? 'Current School Year';
    $currentUserId = session('user_id');
    $currentUser = \App\Models\users::find($currentUserId);
    $collegeId = (int) ($currentUser?->college_id ?? session('college_id') ?? 0);

    $schedules = Schedule::with(['user', 'course', 'room', 'Program'])
        ->where(function ($query) use ($matchingDays, $todayLetter) {
            foreach ($matchingDays as $day) {
                $query->orWhere('day', $day)
                      ->orWhere('day', 'LIKE', "%{$day}%");
            }

            if ($todayLetter !== '') {
                $query->orWhere('day', 'LIKE', "%{$todayLetter}%");
            }
        })
        ->whereHas('user', function ($query) {
            $query->where('role', '!=', 1);
        })
        ->when($collegeId > 0, function ($query) use ($collegeId) {
            $query->whereHas('user', function ($userQuery) use ($collegeId) {
                $userQuery->where('college_id', $collegeId);
            });
        }, function ($query) {
            $query->whereRaw('1 = 0');
        })
        ->orderBy('start_time', 'asc')
        ->get();

    $attendances = report::whereDate('attendance_date', $now->toDateString())
        ->whereIn('schedule_id', $schedules->pluck('id'))
        ->get()
        ->keyBy('schedule_id');

    $facultySchedules = $schedules->map(function ($schedule) use ($now, $attendances) {
        $attendance = $attendances->get($schedule->id);
        $roleLabels = [
            2 => 'Dean',
            3 => 'Assistant Dean',
            4 => 'Faculty',
            5 => 'Program Head',
        ];

        $startDateTime = Carbon::today()->setTimeFromTimeString($schedule->start_time);
        $endDateTime = Carbon::today()->setTimeFromTimeString($schedule->end_time);

        if ($endDateTime->lt($startDateTime)) {
            $endDateTime->addDay();
        }

        $isLive = $now->between($startDateTime, $endDateTime, true);

        return [
            'faculty' => trim(($schedule->user->first_name ?? '') . ' ' . ($schedule->user->last_name ?? '')) ?: 'Faculty',
            'role' => $roleLabels[(int) ($schedule->user->role ?? 0)] ?? 'Role ' . (int) ($schedule->user->role ?? 0),
            'course_code' => $schedule->course?->course_code ?? 'N/A',
            'subject' => $schedule->course?->course_name ?? 'N/A',
            'room' => $schedule->room?->room_name ?? 'N/A',
            'attendance_status' => $attendance?->status_in ?? 'waiting',
            'time_in' => $attendance?->time_in,
            'time_out' => $attendance?->time_out,
            'day' => $schedule->day,
            'date' => $startDateTime->toDateString(),
            'date_display' => $startDateTime->translatedFormat('D, M d, Y'),
            'start' => $startDateTime->format('H:i:s'),
            'end' => $endDateTime->format('H:i:s'),
            'start_display' => $startDateTime->format('g:i A'),
            'end_display' => $endDateTime->format('g:i A'),
            'start_datetime' => $startDateTime,
            'end_datetime' => $endDateTime,
            'is_live' => $isLive,
            'label' => $isLive ? 'In progress' : 'Upcoming',
        ];
    })->sortBy(fn ($item) => $item['start_datetime']->timestamp)->values();

    $nextClass = $facultySchedules->first();

    return view('reports', [
        'facultySchedules' => $facultySchedules,
        'nextClass' => $nextClass,
        'todayLabel' => $now->translatedFormat('l, F d, Y'),
        'currentSemester' => $currentSemester,
        'currentSchoolYear' => $currentSchoolYear,
    ]);
}
   
public function GetAttendance(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|integer',
            'schedule_id' => 'required|integer',
            'room_id' => 'required|integer',
            'time_in' => 'nullable|date_format:H:i:s',
            'time_out' => 'nullable|date_format:H:i:s',
            'attendance_date' => 'nullable|date',
            'status' => 'required|string',

        ]);

        $attendanceRecord = report::CreateAttendance(
            $validatedData['user_id'],
            $validatedData['schedule_id'],
            $validatedData['room_id'],
            $validatedData['time_in'] ?? null,
            $validatedData['time_out'] ?? null,
            $validatedData['attendance_date'] ?? null,
            $validatedData['status'],
             );
            //  logic for checking the schedule for its start time and end time of the class and 
            // check if there is no update
            
            
        return view('dashboard', [
            'attendanceRecord' => $attendanceRecord,
        ]);
    }
    public function generate(Request $request)
    {
        return $this->index();
    }
}
