<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\Schedule;
use App\Models\Report;
use App\Models\room;
use Illuminate\Http\JsonResponse;
use App\Services\AttendanceService;

class ApiController extends Controller
{
    public function __construct(private AttendanceService $attendanceService)
    {
    }

    /**
     * This endpoint keeps the attendance records synchronized for the current day.
     * It is mainly used to refresh the daily report data before the system performs
     * attendance checks or displays the dashboard status.
     */
    public function syncScheduledAttendance(Request $request): JsonResponse
    {
        $now = Carbon::now();
        $schedulesChecked = Report::syncForToday($now);

        return response()->json([
            'date' => $now->toDateString(),
            'schedules_checked' => $schedulesChecked,
        ]);
    }

    /**
     * Validates the scanner payload, delegates attendance decisions, and returns the result.
     */
    public function handleAttendanceScan(Request $request)
    {
        // Extract the RFID number and the room detected by the hardware.
        $scannedUid = $this->resolveUid($request);
        $roomName = $this->resolveRoom($request);

        Log::info('Attendance RFID packet received', [
            'room' => $roomName,
            'has_uid' => $scannedUid !== '',
        ]);

        // If either value is missing, the scan is invalid and should be rejected early.
        if ($scannedUid === '' || $roomName === '') {
            Log::warning('Attendance RFID packet rejected because UID or room is missing.', [
                'has_uid' => $scannedUid !== '',
                'has_room' => $roomName !== '',
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'RFID UID and scanner room are required.',
            ], 422);
        }

        $attendanceData = $this->attendanceService->processScan($scannedUid, $roomName);

        Log::info('Attendance RFID packet processed', [
            'room' => $roomName,
            'status' => $attendanceData['status'] ?? 'unknown',
            'attendance_status' => $attendanceData['attendance_status'] ?? null,
            'message' => $attendanceData['message'] ?? null,
        ]);

        // Store the latest scan result so the admin dashboard can show the most recent update.
        Cache::store('database')->put('latest_attendance_scan_data', $attendanceData, 120);
        Cache::store('database')->put('latest_attendance_scan', $scannedUid, 120);

        // LCD display mode compresses the attendance message into two short lines.
        if ($request->query('format') === 'lcd') {
            $attendanceStatus = $attendanceData['attendance_status'] ?? null;
            $lineOne = $attendanceStatus
                ? 'Status: ' . ucfirst(str_replace('_', ' ', $attendanceStatus))
                : 'Scan: ' . ucfirst((string) ($attendanceData['status'] ?? 'error'));

            if (!empty($attendanceData['time_out'])) {
                $lineTwo = 'Out: ' . $attendanceData['time_out'];
            } elseif (!empty($attendanceData['time_in'])) {
                $lineTwo = 'In: ' . $attendanceData['time_in'];
            } else {
                $lineTwo = $attendanceData['message'] ?? 'No attendance update';
            }

            return response(implode('|', array_map(
                fn ($line) => substr(str_replace('|', ' ', $line), 0, 20),
                [$lineOne, $lineTwo]
            )))->header('Content-Type', 'text/plain');
        }

        return response()->json($attendanceData, 200);
    }

    /**
     * This route captures a raw RFID scan before it is assigned to any attendance action.
     * The system simply stores the most recent UID for monitoring or assignment-related screens.
     */
    public function handleScan(Request $request)
    {
        $scannedUid = $this->resolveUid($request);

        // A blank UID indicates an incomplete hardware transmission.
        if ($scannedUid === '') {
            return response()->json([
                'status' => 'error',
                'message' => 'RFID UID is required.',
            ], 422);
        }

        Log::info("RFID Hardware Scan Received: {$scannedUid}");

        Cache::store('database')->put('latest_assignment_scan', $scannedUid, 120);

        return response()->json(['uid' => $scannedUid], 200);
    }

    /**
     * Returns the last raw assignment scan recorded by the hardware.
     */
    public function checkLatestAssignmentScan()
    {
        return response()->json([
            'uid' => Cache::store('database')->pull('latest_assignment_scan'),
        ]);
    }

    /**
     * Returns the last attendance scan and its detailed processed data for the dashboard.
     */
    public function checkLatestAttendanceScan()
    {
        return response()->json([
            'uid' => Cache::store('database')->get('latest_attendance_scan'),
            'scan_data' => Cache::store('database')->get('latest_attendance_scan_data'),
        ]);
    }

    /**
     * Normalizes the RFID value from different request formats, including POST JSON,
     * form input, and direct query parameters. This keeps the scanner and the app compatible
     * with multiple hardware and frontend implementations.
     */
    private function isScheduleActive(Schedule $schedule, Carbon $now): bool
    {
        $scheduleDays = preg_split('/[\s,\/|&-]+/', strtolower(trim((string) $schedule->day)), -1, PREG_SPLIT_NO_EMPTY);
        $today = strtolower($now->format('l'));
        $shortToday = strtolower($now->format('D'));

        $matchesDay = in_array($today, $scheduleDays, true)
            || in_array($shortToday, $scheduleDays, true);

        if (!$matchesDay) {
            return false;
        }

        if (!$schedule->start_time || !$schedule->end_time) {
            return false;
        }

        $start = Carbon::createFromFormat('H:i:s', $schedule->start_time);
        $end = Carbon::createFromFormat('H:i:s', $schedule->end_time);
        $current = Carbon::createFromFormat('H:i:s', $now->format('H:i:s'));

        return $current->greaterThanOrEqualTo($start) && $current->lessThanOrEqualTo($end);
    }

    private function resolveUid(Request $request): string
    {
        $uid = $request->input('uid')
            ?? $request->input('UID')
            ?? $request->input('card_uid')
            ?? $request->input('rfid');

        if ($uid === null) {
            $rawBody = trim($request->getContent());
            $decodedBody = json_decode($rawBody, true);
            $uid = is_array($decodedBody)
                ? ($decodedBody['uid'] ?? $decodedBody['UID'] ?? $decodedBody['card_uid'] ?? $decodedBody['rfid'] ?? '')
                : $rawBody;
        }

        return strtoupper(trim((string) $uid));
    }

    /**
     * Normalizes the room value from the scanner payload. This is necessary because the
     * hardware may send room names in different field names or casing across devices.
     */
    private function resolveRoom(Request $request): string
    {
        $room = $request->input('room')
            ?? $request->input('room_name')
            ?? $request->input('location')
            ?? $request->input('roomId');

        if ($room === null) {
            $rawBody = trim($request->getContent());
            $decodedBody = json_decode($rawBody, true);
            $room = is_array($decodedBody)
                ? ($decodedBody['room'] ?? $decodedBody['room_name'] ?? $decodedBody['location'] ?? $decodedBody['roomId'] ?? '')
                : '';
        }

        return strtoupper(trim((string) $room));
    }

    /**
     * This endpoint serves five pipe-delimited lines for the room LCD display.
     */
    public function DisplaytoLcd()
    {
        $now = Carbon::now();
        $today = $now->format('l');
        $activeTerm = Report::activeTerm();

        // If a specific room is requested, the LCD shows only that room's current class information.
        if (request()->filled('room')) {
            $roomName = strtoupper(trim((string) request()->query('room')));
            $scannerRoom = room::whereRaw('UPPER(TRIM(room_name)) = ?', [$roomName])->first();

            if (!$scannerRoom) {
                return response('Room not found|Check room setup||', 404)
                    ->header('Content-Type', 'text/plain');
            }

            $schedule = Schedule::with(['User', 'course'])
                ->where('room_id', $scannerRoom->id)
                ->when(
                    $activeTerm,
                    fn ($query) => $query
                        ->where('School_year', $activeTerm->school_year)
                        ->where('Semester', $activeTerm->semester),
                    fn ($query) => $query->whereRaw('1 = 0')
                )
                ->where(function ($query) use ($today) {
                    $query->whereRaw('LOWER(day) LIKE ?', ['%' . strtolower($today) . '%'])
                        ->orWhereRaw('LOWER(day) LIKE ?', ['%' . strtolower(substr($today, 0, 3)) . '%']);
                })
                ->whereTime('start_time', '<=', $now->format('H:i:s'))
                ->whereTime('end_time', '>', $now->format('H:i:s'))
                ->orderBy('start_time')
                ->first();

            $attendance = null;
            if (!$schedule) {
                $attendance = Report::with(['schedule.User', 'schedule.course'])
                    ->where('room_id', $scannerRoom->id)
                    ->whereDate('attendance_date', $now->toDateString())
                    ->whereNotNull('time_in')
                    ->whereNull('time_out')
                    ->whereIn('status', ['ongoing', 'overtime'])
                    ->latest('id')
                    ->first();
                $schedule = $attendance?->schedule;

                if ($attendance && $schedule) {
                    $scheduledStart = Carbon::parse($attendance->attendance_date . ' ' . $schedule->start_time);
                    $scheduledEnd = Carbon::parse($attendance->attendance_date . ' ' . $schedule->end_time);
                    if ($scheduledEnd->lt($scheduledStart)) {
                        $scheduledEnd->addDay();
                    }

                    if ($now->greaterThanOrEqualTo($scheduledEnd)) {
                        $attendance = null;
                        $schedule = null;
                    }
                }
            }

            if ($schedule) {
                $attendance ??= Report::whereDate('attendance_date', $now->toDateString())
                    ->where('schedule_id', $schedule->id)
                    ->first();

                if ($attendance?->status === 'absent') {
                    $attendance = null;
                    $schedule = null;
                }
            }

            if (!$schedule) {
                $lcdLines = [
                    'Faculty: -',
                    'Course: No class',
                    'Time: -',
                    'Attend: -',
                    'Room: ' . ucfirst($scannerRoom->status),
                ];
            } else {
                $faculty = trim(($schedule->User?->first_name ?? '') . ' ' . ($schedule->User?->last_name ?? ''));
                $startTime = Carbon::parse($schedule->start_time)->format('H:i');
                $endTime = Carbon::parse($schedule->end_time)->format('H:i');
                $lcdLines = [
                    'Faculty: ' . ($faculty ?: 'Unknown'),
                    'Course: ' . ($schedule->course?->course_code ?? $schedule->course?->course_name ?? 'Class'),
                    'Time: ' . $startTime . '-' . $endTime,
                    'Attend: ' . ucwords(str_replace('_', ' ', $attendance?->status ?? 'waiting')),
                    'Room: ' . ucfirst($scannerRoom->status),
                ];
            }

            $lcdLines = array_map(
                fn ($line) => substr(str_replace('|', ' ', $line), 0, 20),
                $lcdLines
            );

            return response(implode('|', $lcdLines))
                ->header('Content-Type', 'text/plain');
        }

        // Cache the dashboard payload for a few seconds so repeated page refreshes do not hit the
        // database repeatedly and create a slow render time. The data is updated often enough for
        // this to still feel live without sacrificing responsiveness.
        $cacheKey = 'lcd-room-live-status:' . $today;

        $payload = Cache::remember($cacheKey, 5, function () use ($today, $now) {
            // For the general dashboard view, the system lists all rooms and their live class status.
            $rooms = \App\Models\room::with('building')->select(['id', 'room_name', 'room_type', 'bldg_id', 'status'])->get();

            $allSchedules = Schedule::with(['User:id,first_name,last_name', 'course:id,course_code,course_name', 'room:id,room_name'])
                ->where(function ($query) use ($today) {
                    $query->whereRaw('LOWER(day) LIKE ?', ['%' . strtolower($today) . '%'])
                        ->orWhereRaw('LOWER(day) LIKE ?', ['%' . strtolower(substr($today, 0, 3)) . '%']);
                })
                ->select(['id', 'room_id', 'user_id', 'course_id', 'start_time', 'end_time', 'day'])
                ->get();

            $activeSchedules = $allSchedules->filter(function ($schedule) use ($now) {
                return $this->isScheduleActive($schedule, $now);
            });

            // Include current attendance times alongside the scheduled live-class details.
            $attendanceBySchedule = Report::whereDate('attendance_date', $now->toDateString())
                ->whereIn('schedule_id', $activeSchedules->pluck('id'))
                ->select(['id', 'schedule_id', 'time_in', 'time_out', 'status'])
                ->get()
                ->keyBy('schedule_id');

            $liveByRoom = $activeSchedules->mapWithKeys(function ($schedule) use ($attendanceBySchedule) {
                $attendance = $attendanceBySchedule->get($schedule->id);
                $faculty = trim(($schedule->User?->first_name ?? '') . ' ' . ($schedule->User?->last_name ?? ''));

                return [$schedule->room_id => [
                    'schedule_id' => $schedule->id,
                    'faculty' => $faculty ?: 'Faculty',
                    'course_code' => $schedule->course?->course_code ?? 'N/A',
                    'course' => $schedule->course?->course_name ?? 'N/A',
                    'start' => $schedule->start_time,
                    'end' => $schedule->end_time,
                    'time_in' => $attendance?->time_in,
                    'time_out' => $attendance?->time_out,
                    'attendance_status' => $attendance?->status ?? 'waiting',
                ]];
            });

            return [
                'rooms' => $rooms->map(function ($room) use ($liveByRoom) {
                    $live = $liveByRoom->get($room->id);

                    return [
                        'id' => $room->id,
                        'name' => $room->room_name,
                        'type' => $room->room_type,
                        'bldg_abbr' => $room->building?->bldg_abbr ?? '',
                        'bldg_name' => $room->building?->bldg_name ?? '',
                        'status' => $room->status,
                        'live' => $live,
                    ];
                })->values(),
                'updated_at' => $now->toIso8601String(),
            ];
        });

        return response()->json($payload);
    }
}