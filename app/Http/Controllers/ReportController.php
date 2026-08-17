<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\semyr;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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
        $todayDay = $now->translatedFormat('D');
        $semesterRecord = semyr::latest('id')->first();
        $currentSemester = $semesterRecord?->semester ?? 'Current Semester';
        $currentSchoolYear = $semesterRecord?->school_year ?? 'Current School Year';

        $schedules = Schedule::with(['user', 'course', 'room', 'program'])
            ->where('day', $todayDay)
            ->whereTime('start_time', '>=', '07:00:00')
            ->whereTime('start_time', '<=', '18:00:00')
            ->orderBy('start_time', 'asc')
            ->get();

        $facultySchedules = $schedules->map(function ($schedule) use ($now) {
            $startDateTime = Carbon::today()->setTimeFromTimeString($schedule->start_time);
            $endDateTime = Carbon::today()->setTimeFromTimeString($schedule->end_time);

            if ($endDateTime->lt($startDateTime)) {
                $endDateTime->addDay();
            }

            $isLive = $now->between($startDateTime, $endDateTime, true);

            return [
                'faculty' => trim(($schedule->user->first_name ?? '') . ' ' . ($schedule->user->last_name ?? '')) ?: 'Faculty',
                'course_code' => $schedule->course?->course_code ?? 'N/A',
                'subject' => $schedule->course?->course_name ?? 'N/A',
                'room' => $schedule->room?->room_name ?? 'N/A',
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

    public function generate(Request $request)
    {
        return $this->index();
    }
}
