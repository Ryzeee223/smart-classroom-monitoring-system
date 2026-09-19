<?php
namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\semyr;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function reportDisplay()
    {
        $displayrep = Report::with('attendance_date');
        return view('reports', compact('displayrep'));
    }
    public function index(Request $request)
    {
        $selectedDate = $request->query('date');
        $now = $selectedDate ? Carbon::parse($selectedDate) : Carbon::now();
        $todayDate = $now->toDateString();
        $todayFullDay = $now->format('l'); // e.g. "Monday"
        $todayShortDay = $now->format('D'); // e.g. "Mon"

        $semesterRecord = semyr::latest('id')->first();
        $currentSemester = $semesterRecord?->semester ?? 'Current Semester';
        $currentSchoolYear = $semesterRecord?->school_year ?? 'Current School Year';
        
        $currentUserId = session('user_id');
        $currentUser = \App\Models\users::find($currentUserId);
        $collegeId = (int) ($currentUser?->college_id ?? session('college_id') ?? 0);

        // Attendance is the source of report rows. Schedule only supplies
        // the planned class time and course code for each attendance record.
        $attendances = Report::with(['user', 'schedule.course'])
            ->whereDate('attendance_date', $todayDate)
            ->where('status', '!=', 'waiting')
            ->when($collegeId > 0, fn ($query) => $query->where('college_id', $collegeId))
            ->get();

        $displayrep = Report::whereNotNull('attendance_date')
            ->where('status', '!=', 'waiting')
            ->when($collegeId > 0, fn ($query) => $query->where('college_id', $collegeId))
            ->orderByDesc('attendance_date')
            ->pluck('attendance_date')
            ->filter()
            ->map(fn ($date) => Carbon::parse($date)->toDateString())
            ->unique()
            ->values();

        $facultySchedules = $attendances->map(function ($attendance) use ($now, $todayDate) {
            $schedule = $attendance->schedule;
            $roleLabels = [
                2 => 'Dean',
                3 => 'Assistant Dean',
                4 => 'Faculty',
                5 => 'Program Head',
            ];

            $startDateTime = $schedule?->start_time
                ? Carbon::parse("{$todayDate} {$schedule->start_time}")
                : null;
            $endDateTime = $schedule?->end_time
                ? Carbon::parse("{$todayDate} {$schedule->end_time}")
                : null;

            if ($startDateTime && $endDateTime && $endDateTime->lt($startDateTime)) {
                $endDateTime->addDay();
            }

            $isLive = $startDateTime && $endDateTime
                && $now->toDateString() === $todayDate
                && $now->between($startDateTime, $endDateTime, true);
            $faculty = $attendance->user;

            return [
                'faculty' => trim(($faculty?->first_name ?? '') . ' ' . ($faculty?->last_name ?? '')) ?: 'Faculty',
                'role' => $roleLabels[(int) ($faculty?->role ?? 0)] ?? 'Role ' . (int) ($faculty?->role ?? 0),
                'course_code' => $schedule?->course?->course_code ?? 'N/A',
                'subject' => $schedule?->course?->course_name ?? 'N/A',
                'room' => $attendance->room_id ?? 'N/A',
                'attendance_status' => $attendance->status ?? 'waiting',
                'time_in' => $attendance->time_in,
                'time_out' => $attendance->time_out,
                'day' => $attendance->day,
                'date' => $todayDate,
                'date_display' => $startDateTime?->translatedFormat('D, M d, Y') ?? $todayDate,
                'start' => $startDateTime?->format('H:i:s'),
                'end' => $endDateTime?->format('H:i:s'),
                'start_display' => $startDateTime?->format('g:i A') ?? 'N/A',
                'end_display' => $endDateTime?->format('g:i A') ?? 'N/A',
                'start_datetime' => $startDateTime,
                'end_datetime' => $endDateTime,
                'is_live' => $isLive,
                'label' => $isLive ? 'In progress' : 'Attendance recorded',
            ];
        })->sortBy(fn ($item) => $item['start_datetime']?->timestamp ?? PHP_INT_MAX)->values();

        $nextClass = $facultySchedules->first();

        return view('reports', [
            'facultySchedules' => $facultySchedules,
            'nextClass' => $nextClass,
            'todayLabel' => $now->translatedFormat('l, F d, Y'),
            'currentSemester' => $currentSemester,
            'currentSchoolYear' => $currentSchoolYear,
            'displayrep' => $displayrep,
            'selectedDate' => $todayDate,
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
        $validated = $request->validate([
            'date' => 'required|date',
        ]);
        $semesterYear = semyr::latest('id')->first();
        $Semester = $semesterYear?->semester ?? 'Current Semester';
        $SchoolYear = $semesterYear?->school_year ?? 'Current School Year';
        $currentUser = \App\Models\users::find(session('user_id'));
        $collegeId = (int) ($currentUser?->college_id ?? session('college_id') ?? 0);
        $reportDate = Carbon::parse($validated['date']);
        $attendanceRecords = Report::with(['user', 'schedule.course'])
            ->whereDate('attendance_date', $reportDate->toDateString())
            ->where('status', '!=', 'waiting')
            ->when($collegeId > 0, fn ($query) => $query->where('college_id', $collegeId))
            ->get()
            ->sortBy(fn ($record) => $record->schedule?->start_time ?? '23:59:59');

        $escape = static fn ($value) => htmlspecialchars((string) ($value ?? 'N/A'), ENT_QUOTES, 'UTF-8');

        return response()->streamDownload(function () use ($attendanceRecords, $escape, $reportDate, $Semester, $SchoolYear) {
            echo '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body>';
            echo '<h2>Attendance Report - ' . $escape($reportDate->format('F d, Y')) . '</h2>';
            echo '<h2>School year: ' . $escape($SchoolYear) . ' | ' . $escape($Semester) . '</h2>';
            echo '<table border="1">';
            echo '<thead><tr><th>Time</th><th>Time In</th><th>Time Out</th><th>Faculty Name</th><th>Course Code</th><th>Status</th></tr></thead><tbody>';

            foreach ($attendanceRecords as $record) {
                $schedule = $record->schedule;
                $faculty = trim(($record->user?->first_name ?? '') . ' ' . ($record->user?->last_name ?? '')) ?: 'N/A';
                echo '<tr>';
                echo '<td>' . $escape($schedule?->start_time) . ' - ' . $escape($schedule?->end_time) . '</td>';
                echo '<td>' . $escape($record->time_in) . '</td>';
                echo '<td>' . $escape($record->time_out) . '</td>';
                echo '<td>' . $escape($faculty) . '</td>';
                echo '<td>' . $escape($schedule?->course?->course_code) . '</td>';
                echo '<td>' . $escape(ucfirst(str_replace('_', ' ', $record->status ?? 'waiting'))) . '</td>';
                echo '</tr>';
            }

            if ($attendanceRecords->isEmpty()) {
                echo '<tr><td colspan="6">No attendance records found.</td></tr>';
            }

            echo '</tbody></table></body></html>';
        }, 'attendance-report-' . $reportDate->format('Y-m-d') . '.xls', [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        ]);
    }
}