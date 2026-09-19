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
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    public function handleAttendanceScan(Request $request)
    {
        $scannedUid = $this->resolveUid($request);

        if ($scannedUid === '') {
            return response()->json([
                'status' => 'error',
                'message' => 'RFID UID is required.',
            ], 422);
        }

        $user = User::whereRaw('UPPER(TRIM(RFID_code)) = ?', [$scannedUid])->first();
        $attendanceData = $user
            ? Cache::lock(
                'attendance-scan:' . $user->id . ':' . Carbon::today()->toDateString(),
                10
            )->block(5, fn () => $this->processAttendanceForUser($user, $scannedUid))
            : [
                'status' => 'denied',
                'message' => 'RFID card is not assigned to a user.',
                'uid' => $scannedUid,
            ];

        Cache::store('database')->put('latest_attendance_scan_data', $attendanceData, 120);
        Cache::store('database')->put('latest_attendance_scan', $scannedUid, 120);

        return response()->json($attendanceData, 200);
    }

    public function handleScan(Request $request)
    {
        $scannedUid = $this->resolveUid($request);

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

    public function checkLatestAssignmentScan()
    {
        return response()->json([
            'uid' => Cache::store('database')->get('latest_assignment_scan'),
        ]);
    }

    public function checkLatestAttendanceScan()
    {
        return response()->json([
            'uid' => Cache::store('database')->get('latest_attendance_scan'),
            'scan_data' => Cache::store('database')->get('latest_attendance_scan_data'),
        ]);
    }

    private function processAttendanceForUser(User $user, string $scannedUid): array
    {
        $now = Carbon::now();
        $today = $now->format('l');

        $schedule = Schedule::where('user_id', $user->id)
            ->where('day', $today)
            ->whereTime('start_time', '<=', $now->format('H:i:s'))
            ->whereTime('end_time', '>=', $now->format('H:i:s'))
            ->first();

        $attendance = null;

        // A checkout scan happens after the schedule is no longer active.
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

        if (!$schedule) {
            return [
                'status' => 'denied',
                'message' => 'User found, but no active schedule for this room and time.',
                'uid' => $scannedUid,
                'user' => [
                    'id' => $user->id,
                    'name' => trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')),
                ],
            ];
        }

        $scheduledUser = $schedule->User;
        $scheduledUid = strtoupper(trim((string) ($scheduledUser?->RFID_code ?? '')));

        if (!$scheduledUser
            || (int) $scheduledUser->id !== (int) $user->id
            || $scheduledUid === ''
            || $scheduledUid !== $scannedUid
        ) {
            Log::warning('Attendance scan rejected because the card does not belong to the scheduled faculty.', [
                'schedule_id' => $schedule->id,
                'scheduled_user_id' => $schedule->user_id,
                'scanned_user_id' => $user->id,
            ]);

            return [
                'status' => 'denied',
                'message' => 'This RFID card is not assigned to the scheduled faculty.',
                'uid' => $scannedUid,
            ];
        }

        if (!$schedule->room_id) {
            Log::error('Attendance scan skipped because the schedule has no room.', [
                'schedule_id' => $schedule->id,
                'user_id' => $user->id,
            ]);

            return [
                'status' => 'denied',
                'message' => 'This schedule is missing a room assignment.',
                'uid' => $scannedUid,
            ];
        }

        $start = Carbon::today()->setTimeFromTimeString($schedule->start_time);
        $end = Carbon::today()->setTimeFromTimeString($schedule->end_time);

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

        $accountStatus = strtolower(str_replace(['-', '_'], ' ', trim((string) $user->acc_status)));
        $isOnLeave = in_array($accountStatus, ['sick leave', 'on leave', 'leave', 'sick'], true);

        // checking for user status before turning the attendance to all on leave
        if ($isOnLeave) {
            $attendance->time_in = $attendance->time_in ?? $now->format('H:i:s');
            $attendance->status = 'on_leave';
            $attendance->save();
            $schedule->room()->update(['status' => 'occupied']);

            $user->update(['acc_status' => 'On Leave']);

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

        if (empty($attendance->time_in) && empty($attendance->time_out)) {
            $attendance->time_in = $now->format('H:i:s');
            $attendance->status = $now->gt($start->copy()->addMinutes(30)) ? 'late' : 'attended';
            $attendance->save();
            $schedule->room()->update(['status' => 'occupied']);

            $user->update(['acc_status' => ucfirst($attendance->status)]);

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

            $attendance->time_out = $now->format('H:i:s');
            $attendance->save();

            $roomStillInUse = Report::where('room_id', $schedule->room_id)
                ->whereDate('attendance_date', $now->toDateString())
                ->whereNotNull('time_in')
                ->whereNull('time_out')
                ->where('id', '!=', $attendance->id)
                ->exists();

            if (!$roomStillInUse) {
                $schedule->room()->update(['status' => 'vacant']);
            }

            $user->update(['acc_status' => 'Checked Out']);

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
    public function DisplaytoLcd()
    {
        $now = Carbon::now();
        $today = $now->format('l');

        $rooms = \App\Models\room::with('building')->get();
        $schedules = Schedule::with(['User', 'course', 'room'])
            ->where(function ($query) use ($today) {
                $query->whereRaw('LOWER(day) LIKE ?', ['%' . strtolower($today) . '%'])
                    ->orWhereRaw('LOWER(day) LIKE ?', ['%' . strtolower(substr($today, 0, 3)) . '%']);
            })
            ->whereTime('start_time', '<=', $now->format('H:i:s'))
            ->whereTime('end_time', '>=', $now->format('H:i:s'))
            ->get();

        $attendanceBySchedule = Report::whereDate('attendance_date', $now->toDateString())
            ->whereIn('schedule_id', $schedules->pluck('id'))
            ->get()
            ->keyBy('schedule_id');

        $liveByRoom = $schedules->mapWithKeys(function ($schedule) use ($attendanceBySchedule) {
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
                'occupied' => (bool) ($attendance?->time_in && !$attendance?->time_out),
            ]];
        });

        return response()->json([
            'rooms' => $rooms->map(function ($room) use ($liveByRoom) {
                $live = $liveByRoom->get($room->id);

                return [
                    'id' => $room->id,
                    'name' => $room->room_name,
                    'type' => $room->room_type,
                    'bldg_abbr' => $room->building?->bldg_abbr ?? '',
                    'bldg_name' => $room->building?->bldg_name ?? '',
                    'status' => $live && $live['occupied'] ? 'occupied' : 'vacant',
                    'live' => $live,
                ];
            })->values(),
            'updated_at' => $now->toIso8601String(),
        ]);
    }
}