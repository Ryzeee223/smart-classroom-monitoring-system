<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\Schedule;
use App\Models\User;
use App\Models\Report;
use App\Models\room;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
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
     * Main RFID attendance processing endpoint.
     * The scanner sends the card UID and room location, then this method verifies the
     * user, checks the schedule, and determines whether the person is checking in,
     * checking out, or being denied access.
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

        // Find the registered user assigned to the scanned RFID card.
        $userQuery = User::query();
        $rfidColumn = $userQuery->getQuery()->getGrammar()->wrap('RFID_code');
        $user = $userQuery
            ->whereRaw("UPPER(TRIM({$rfidColumn})) = ?", [$scannedUid])
            ->first();

        // A valid user goes through the attendance workflow; an unassigned card is denied.
        $attendanceData = $user
            ? Cache::lock(
                'attendance-scan:' . $user->id . ':' . Carbon::today()->toDateString(),
                10
            )->block(5, fn () => $this->processAttendanceForUser($user, $scannedUid, $roomName))
            : [
                'status' => 'denied',
                'message' => 'card is not assigned',
                'uid' => $scannedUid,
            ];

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
     * This is the core logic that decides whether a scanned RFID card is allowed to
     * check in or check out for a scheduled class. It validates the room, verifies the
     * schedule assignment, and then updates the attendance record based on the time and status.
     */
    private function processAttendanceForUser(User $user, string $scannedUid, ?string $roomName = null): array
    {
        // Capture the current server time and the day information used for schedule matching.
        $now = Carbon::now();
        $today = $now->format('l');
        $shortToday = $now->format('D');
        $normalizedRoom = trim((string) ($roomName ?? ''));

        // Normalize the room name so the scanner location matches the database values.
        $roomValue = strtoupper(trim($normalizedRoom));
        $allowedRoomIds = room::whereRaw('UPPER(TRIM(room_name)) = ?', [$roomValue])
            ->pluck('id')
            ->all();

        // If the scanner is in an unknown room, the attempt is rejected immediately.
        if (empty($allowedRoomIds)) {
            return [
                'status' => 'denied',
                'message' => 'This room not exist .',
                'uid' => $scannedUid,
                'room' => $normalizedRoom,
                'user' => [
                    'id' => $user->id,
                    'name' => trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')),
                ],
            ];
        }

        // Find the user's active schedule first, then validate its room against the scanner.
        $scheduleQuery = Schedule::where('user_id', $user->id)
            ->where(function ($query) use ($today, $shortToday) {
                $query->whereRaw('LOWER(day) LIKE ?', ['%' . strtolower($today) . '%'])
                    ->orWhereRaw('LOWER(day) LIKE ?', ['%' . strtolower($shortToday) . '%']);
            });

        $schedule = $scheduleQuery
            ->whereTime('start_time', '<=', $now->format('H:i:s'))
            ->whereTime('end_time', '>=', $now->format('H:i:s'))
            ->first();

        $attendance = null;

        // If the schedule is not active at this exact moment, the system checks whether
        // the person is already inside the room and doing a checkout after class end time.
        if (!$schedule) {
            $attendance = Report::with('schedule')
                ->where('user_id', $user->id)
                ->whereDate('attendance_date', $now->toDateString())
                ->whereNotNull('time_in')
                ->whereNull('time_out')
                ->latest('id')
                ->first();
            $schedule = $attendance?->schedule;
        }

        // No active period and no open attendance means there is no valid class activity for this scan.
        if (!$schedule) {
            return [
                'status' => 'denied',
                'message' => 'No active schedule',
                'uid' => $scannedUid,
                'room' => $normalizedRoom,
                'user' => [
                    'id' => $user->id,
                    'name' => trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')),
                ],
            ];
        }

        if (!$schedule->room_id || !in_array((int) $schedule->room_id, array_map('intval', $allowedRoomIds), true)) {
            Log::warning('Attendance scan rejected because the schedule room does not match the scanner room.', [
                'schedule_id' => $schedule->id,
                'schedule_room_id' => $schedule->room_id,
                'scanned_room' => $normalizedRoom,
            ]);

            return [
                'status' => 'denied',
                'message' => 'This RFID card is not assigned to the scheduled room.',
                'uid' => $scannedUid,
                'room' => $normalizedRoom,
            ];
        }

        // This confirms that the scanned card belongs to the faculty assigned to the schedule.
        $scheduledUser = $schedule->User;
        $scheduledUid = strtoupper(trim((string) ($scheduledUser?->RFID_code ?? '')));

        if (!$scheduledUser
            || (int) $scheduledUser->id !== (int) $user->id
            || $scheduledUid === ''
            || $scheduledUid !== $scannedUid
        ) {
            Log::warning('rejected.', [
                'schedule_id' => $schedule->id,
                'scheduled_user_id' => $schedule->user_id,
                'scanned_user_id' => $user->id,
            ]);

            return [
                'status' => 'denied',
                'message' => 'not assigned to faculty.',
                'uid' => $scannedUid,
            ];
        }

        // The schedule end time determines the checkout grace deadline.
        $end = Carbon::today()->setTimeFromTimeString($schedule->end_time);
        $checkoutDeadline = $end->copy()->addMinutes(Report::CHECKOUT_GRACE_MINUTES);

        // Create a report entry if this is the first attendance action for the class period.
        $attendance ??= Report::firstOrCreate(
            [
                'user_id' => $user->id,
                'schedule_id' => $schedule->id,
                'attendance_date' => $now->toDateString(),
            ],
            [
                'room_id' => $schedule->room_id,
                'college_id' => $schedule->User?->college_id,
                'day' => $schedule->day,
                'time_in' => null,
                'time_out' => null,
                'status' => 'waiting',
            ]
        );

        // User leave status is checked before normal attendance processing.
        $accountStatus = strtolower(str_replace(['-', '_'], ' ', trim((string) $user->acc_status)));
        $isOnLeave = in_array($accountStatus, ['sick leave', 'on leave', 'leave', 'sick'], true);

        // A leave status is treated as a valid attendance case, but it is tracked separately
        // so the system can still record the scan without marking the faculty as present.
        if ($isOnLeave) {
            $attendance->time_in = $attendance->time_in ?? $now->format('H:i:s');
            $attendance->status = 'on_leave';
            $attendance->save();
            $schedule->room()->update(['status' => 'occupied']);

            return [
                'status' => 'accepted',
                'attendance_status' => 'on_leave',
                'status_in' => 'on_leave',
                'time_in' => $attendance->time_in,
                'time_out' => $attendance->time_out,
                'message' => 'On leave',
                'uid' => $scannedUid,
                'user' => [
                    'id' => $user->id,
                    'name' => trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')),
                ],
                'attendance_id' => $attendance->id,
            ];
        }

        // A check-in stays ongoing until a checkout scan records time_out.
        if (empty($attendance->time_in) && empty($attendance->time_out)) {
            $attendance->time_in = $now->format('H:i:s');
            $attendance->status = 'ongoing';
            $attendance->save();
            $schedule->room()->update(['status' => 'occupied']);

            return [
                'status' => 'accepted',
                'attendance_status' => $attendance->status,
                'status_in' => $attendance->status,
                'time_in' => $attendance->time_in,
                'time_out' => $attendance->time_out,
                'message' => ucfirst($attendance->status),
                'uid' => $scannedUid,
                'user' => [
                    'id' => $user->id,
                    'name' => trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')),
                ],
                'attendance_id' => $attendance->id,
            ];
        }

        // A second tap while the class is still active is ignored. This prevents duplicate scans
        // from corrupting or double-counting the attendance record before class end time.
        if (!empty($attendance->time_in) && empty($attendance->time_out)) {
            if ($now->lt($end)) {
                return [
                    'status' => 'ignored',
                    'attendance_status' => $attendance->status,
                    'status_in' => $attendance->status,
                    'time_in' => $attendance->time_in,
                    'time_out' => $attendance->time_out,
                    'message' => 'Duplicate tap ignored before class end time.',
                    'uid' => $scannedUid,
                    'user' => [
                        'id' => $user->id,
                        'name' => trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')),
                    ],
                    'attendance_id' => $attendance->id,
                ];
            }

            if ($now->greaterThanOrEqualTo($checkoutDeadline)) {
                $attendance->status = 'absent';
                $attendance->save();

                return [
                    'status' => 'ignored',
                    'attendance_status' => $attendance->status,
                    'status_in' => $attendance->status,
                    'time_in' => $attendance->time_in,
                    'time_out' => $attendance->time_out,
                    'message' => 'Checkout grace period expired.',
                    'uid' => $scannedUid,
                    'user' => [
                        'id' => $user->id,
                        'name' => trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')),
                    ],
                    'attendance_id' => $attendance->id,
                ];
            }

            // A scan during the grace period records checkout and completes attendance.
            $attendance->time_out = $now->format('H:i:s');
            $attendance->status = 'attended';
            $attendance->save();

            // If nobody else is still inside the room, this room returns to vacant status.
            $roomStillInUse = Report::where('room_id', $schedule->room_id)
                ->whereDate('attendance_date', $now->toDateString())
                ->whereNotNull('time_in')
                ->whereNull('time_out')
                ->whereIn('status', ['ongoing', 'on_leave'])
                ->where('id', '!=', $attendance->id)
                ->exists();

            if (!$roomStillInUse) {
                $schedule->room()->update(['status' => 'vacant']);
            }

            return [
                'status' => 'accepted',
                'attendance_status' => $attendance->status,
                'status_in' => $attendance->status,
                'time_in' => $attendance->time_in,
                'time_out' => $attendance->time_out,
                'message' => 'Checked out',
                'uid' => $scannedUid,
                'user' => [
                    'id' => $user->id,
                    'name' => trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')),
                ],
                'attendance_id' => $attendance->id,
            ];
        }

        // If both times are already stored, the scan is simply acknowledged as already completed.
        return [
            'status' => 'accepted',
            'attendance_status' => $attendance->status,
            'status_in' => $attendance->status,
            'time_in' => $attendance->time_in,
            'time_out' => $attendance->time_out,
            'message' => 'Attendance already recorded.',
            'uid' => $scannedUid,
            'user' => [
                'id' => $user->id,
                'name' => trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')),
            ],
            'attendance_id' => $attendance->id,
        ];
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
     * This endpoint serves the LCD display by showing either the currently active class in a room
     * or a message indicating that the room is idle. It is designed for hardware screens that need
     * a minimal two-line text format.
     */
    public function DisplaytoLcd()
    {
        $now = Carbon::now();
        $today = $now->format('l');

        // If a specific room is requested, the LCD shows only that room's current class information.
        if (request()->filled('room')) {
            $roomName = strtoupper(trim((string) request()->query('room')));
            $scannerRoom = room::whereRaw('UPPER(TRIM(room_name)) = ?', [$roomName])->first();

            if (!$scannerRoom) {
                return response('Room not found|Check room setup', 404)
                    ->header('Content-Type', 'text/plain');
            }

            $schedule = Schedule::with(['User', 'course'])
                ->where('room_id', $scannerRoom->id)
                ->where(function ($query) use ($today) {
                    $query->whereRaw('LOWER(day) LIKE ?', ['%' . strtolower($today) . '%'])
                        ->orWhereRaw('LOWER(day) LIKE ?', ['%' . strtolower(substr($today, 0, 3)) . '%']);
                })
                ->whereTime('start_time', '<=', $now->format('H:i:s'))
                ->whereTime('end_time', '>=', $now->format('H:i:s'))
                ->orderBy('start_time')
                ->first();

            if (!$schedule) {
                $lcdLines = ['No ongoing class', 'Room: ' . $scannerRoom->room_name];
            } else {
                $faculty = trim(($schedule->User?->first_name ?? '') . ' ' . ($schedule->User?->last_name ?? ''));
                $lcdLines = [
                    $schedule->course?->course_code ?? $schedule->course?->course_name ?? 'Class in session',
                    $faculty ?: 'Faculty',
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
        $cacheKey = 'lcd-room-live-status:' . $today . ':' . $now->format('H:i:s');

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