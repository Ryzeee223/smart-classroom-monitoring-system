<?php
namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\semyr;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $todayDate = $now->toDateString();
        $todayFullDay = $now->format('l'); // e.g. "Monday"
        $todayShortDay = $now->format('D'); // e.g. "Mon"

        $semesterRecord = semyr::latest('id')->first();
        $currentSemester = $semesterRecord?->semester ?? 'Current Semester';
        $currentSchoolYear = $semesterRecord?->school_year ?? 'Current School Year';
        
        $currentUserId = session('user_id');
        $currentUser = \App\Models\users::find($currentUserId);
        $collegeId = (int) ($currentUser?->college_id ?? session('college_id') ?? 0);

        // 1. Fetch relevant schedules for today
        $schedules = Schedule::with(['user', 'course', 'room', 'Program'])
            ->where(function ($query) use ($todayFullDay, $todayShortDay) {
                $query->whereRaw('LOWER(day) LIKE ?', ['%' . strtolower($todayFullDay) . '%'])
                      ->orWhereRaw('LOWER(day) LIKE ?', ['%' . strtolower($todayShortDay) . '%']);
            })
            ->whereHas('user', function ($query) {
                $query->where('role', '!=', 1);
            })
            ->when($collegeId > 0, function ($query) use ($collegeId) {
                $query->whereHas('user', fn ($uQ) => $uQ->where('college_id', $collegeId));
            }, fn ($query) => $query->whereRaw('1 = 0'))
            ->orderBy('start_time', 'asc')
            ->get();

        // 2. AUTO-CREATE missing attendance rows so new schedules show up immediately
        foreach ($schedules as $schedule) {
            Report::firstOrCreate(
                [
                    'user_id' => $schedule->user_id,
                    'schedule_id' => $schedule->id,
                    'attendance_date' => $todayDate,
                ],
                [
                    'room_id' => $schedule->room_id,
                    'day' => $schedule->day,
                    'time_in' => null,
                    'time_out' => null,
                    'status' => 'waiting',
                ]
            );
        }

        // 3. Fetch all attendance records for today's matching schedules
        $attendances = Report::whereDate('attendance_date', $todayDate)
            ->whereIn('schedule_id', $schedules->pluck('id'))
            ->get()
            ->keyBy('schedule_id');

        // 4. Map schedules for view display
        $facultySchedules = $schedules->map(function ($schedule) use ($now, $todayDate, $attendances) {
            $attendance = $attendances->get($schedule->id);
            
            $roleLabels = [
                2 => 'Dean',
                3 => 'Assistant Dean',
                4 => 'Faculty',
                5 => 'Program Head',
            ];

            $startDateTime = Carbon::parse("{$todayDate} {$schedule->start_time}");
            $endDateTime = Carbon::parse("{$todayDate} {$schedule->end_time}");

            if ($endDateTime->lt($startDateTime)) {
                $endDateTime->addDay();
            }

            $isLive = $now->between($startDateTime, $endDateTime, true);

            return [
                'faculty' => trim(($schedule->user?->first_name ?? '') . ' ' . ($schedule->user?->last_name ?? '')) ?: 'Faculty',
                'role' => $roleLabels[(int) ($schedule->user?->role ?? 0)] ?? 'Role ' . (int) ($schedule->user?->role ?? 0),
                'course_code' => $schedule->course?->course_code ?? 'N/A',
                'subject' => $schedule->course?->course_name ?? 'N/A',
                'room' => $schedule->room?->room_name ?? 'N/A',
                'attendance_status' => $attendance?->status ?? 'waiting',
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

        $attendanceRecord = Report::CreateAttendance(
            $validatedData['user_id'],
            $validatedData['schedule_id'],
            $validatedData['time_in'] ?? null,
            $validatedData['time_out'] ?? null,
            $validatedData['attendance_date'] ?? null,
            $validatedData['status'],
            null
        );

        return redirect()->back()->with('success', 'Attendance updated successfully.');
    }

    public function generate(Request $request)
    {
        return $this->index();
    }
}