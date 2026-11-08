<?php

namespace App\Services;

use App\Models\Report;
use App\Models\room;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AttendanceService
{
    public function processScan(string $scannedUid, string $roomName): array
    {
        $userQuery = User::query();
        $rfidColumn = $userQuery->getQuery()->getGrammar()->wrap('RFID_code');
        $user = $userQuery
            ->whereRaw("UPPER(TRIM({$rfidColumn})) = ?", [$scannedUid])
            ->first();

        if (!$user) {
            return [
                'status' => 'denied',
                'message' => 'card is not assigned',
                'uid' => $scannedUid,
            ];
        }

        return Cache::lock(
            'attendance-scan:' . $user->id . ':' . Carbon::today()->toDateString(),
            10
        )->block(5, fn () => $this->processAttendance($user, $scannedUid, $roomName));
    }

    /**
     * This is the core logic that decides whether a scanned RFID card is allowed to
     * check in or check out for a scheduled class. It validates the room, verifies the
     * schedule assignment, and then updates the attendance record based on the time and status.
     */
    private function processAttendance(User $user, string $scannedUid, ?string $roomName = null): array
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
        $activeTerm = Report::activeTerm();
        $scheduleQuery = Schedule::where('user_id', $user->id)
            ->when(
                $activeTerm,
                fn ($query) => $query
                    ->where('School_year', $activeTerm->school_year)
                    ->where('Semester', $activeTerm->semester),
                fn ($query) => $query->whereRaw('1 = 0')
            )
            ->where(function ($query) use ($today, $shortToday) {
                $query->whereRaw('LOWER(day) LIKE ?', ['%' . strtolower($today) . '%'])
                    ->orWhereRaw('LOWER(day) LIKE ?', ['%' . strtolower($shortToday) . '%']);
            });

        $schedule = $scheduleQuery
            ->whereTime('start_time', '<=', $now->copy()->addMinutes(Report::TIME_IN_GRACE_MINUTES)->format('H:i:s'))
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
            Log::warning('Room mismatch for the schedule', [
                'schedule_id' => $schedule->id,
                'schedule_room_id' => $schedule->room_id,
                'scanned_room' => $normalizedRoom,
            ]);

            return [
                'status' => 'denied',
                'message' => 'No Scheduled Room.',
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
        $earlyCheck = $end->copy()->subMinutes(Report::EARLY_CHECKOUT);
        $checkoutDeadline = $end->copy()->addMinutes(Report::CHECKOUT_GRACE_MINUTES);

        // Create a report entry if this is the first attendance action for the class period.
        $attendance ??= Report::findOrCreateForSchedule($schedule, $now->toDateString());

        // User leave status is checked before normal attendance processing.
        $accountStatus = strtolower(str_replace(['-', '_'], ' ', trim((string) $user->acc_status)));
        $isOnLeave = in_array($accountStatus, ['sick leave', 'on leave', 'leave', 'sick'], true);

        // A leave status is treated as a valid attendance case, but it is tracked separately
        // so the system can still record the scan without marking the faculty as present.
        if ($isOnLeave) {
            $attendance->time_in = $attendance->time_in ?? $now->format('H:i:s');
            $attendance->status = 'on_leave';
            $attendance->save();
            $schedule->room()->update(['status' => 'vacant']);

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

        // A second tap before the early-checkout window is ignored.
        if (!empty($attendance->time_in) && empty($attendance->time_out)) {
            if ($now->lt($earlyCheck)) {
                return [
                    'status' => 'ignored',
                    'attendance_status' => $attendance->status,
                    'status_in' => $attendance->status,
                    'time_in' => $attendance->time_in,
                    'time_out' => $attendance->time_out,
                    'message' => 'Checkout is only allowed during the final 30 minutes of class.',
                    'uid' => $scannedUid,
                    'user' => [
                        'id' => $user->id,
                        'name' => trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')),
                    ],
                    'attendance_id' => $attendance->id,
                ];
            }

            if ($now->greaterThanOrEqualTo($checkoutDeadline)) {
                $attendance->time_out = $now->format('H:i:s');
                $attendance->status = 'overtime';
                $schedule->room()->update(['status' => 'vacant']);

                $attendance->save();

                return [
                    'status' => 'accepted',
                    'attendance_status' => $attendance->status,
                    'status_in' => $attendance->status,
                    'time_in' => $attendance->time_in,
                    'time_out' => $attendance->time_out,
                    'message' => 'Overtime checkout recorded.',
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
            $attendance->status = $now->lt($end) ? 'early_ended' : 'attended';
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
}
