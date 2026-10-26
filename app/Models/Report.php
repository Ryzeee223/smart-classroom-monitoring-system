<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Report extends Model
{
    public const TIME_IN_GRACE_MINUTES = 30;
    public const CHECKOUT_GRACE_MINUTES = 3;
    public const EARLY_CHECKOUT = 30;
    protected $table = 'attendance';

    protected $fillable = [
        'user_id',
        'college_id',
        'schedule_id',
        'room_id',
        'day',
        'time_in',
        'time_out',
        'attendance_date',
        'status',
    ];

    protected static function booted(): void
    {
        static::saved(function (self $attendance): void {
            if (!$attendance->room_id) {
                return;
            }

            $roomHasOpenAttendance = self::where('room_id', $attendance->room_id)
                ->whereDate('attendance_date', Carbon::today()->toDateString())
                ->whereNotNull('time_in')
                ->whereNull('time_out')
                ->whereIn('status', ['ongoing', 'on_leave'])
                ->exists();

            room::whereKey($attendance->room_id)->update([
                'status' => $roomHasOpenAttendance ? 'occupied' : 'vacant',
            ]);
        });
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    public function user()
    {
        return $this->belongsTo(users::class, 'user_id');
    }

    public function college()
    {
        return $this->belongsTo(college::class, 'college_id');
    }

    public static function syncForSchedule(Schedule $schedule, string $attendanceDate, Carbon $now): ?self
    {
        $attendanceDate = Carbon::parse($attendanceDate)->toDateString();
        $scheduledDays = preg_split('/[\s,\/|&-]+/', strtolower(trim((string) $schedule->day)), -1, PREG_SPLIT_NO_EMPTY);
        $attendanceDays = [
            strtolower(Carbon::parse($attendanceDate)->format('l')),
            strtolower(Carbon::parse($attendanceDate)->format('D')),
        ];

        if (!array_intersect($scheduledDays, $attendanceDays)) {
            return null;
        }

        $attendance = self::firstOrCreate(
            [
                'user_id' => $schedule->user_id,
                'schedule_id' => $schedule->id,
                'attendance_date' => $attendanceDate,
            ],
            [
                'room_id' => $schedule->room_id,
                'college_id' => $schedule->User?->college_id,
                'day' => $schedule->day,
                'status' => 'waiting',
            ]
        );

        $accountStatus = strtolower(str_replace(['-', '_'], ' ', trim((string) $schedule->User?->acc_status)));
        $isOnLeave = str_contains($accountStatus, 'sick') || str_contains($accountStatus, 'leave');
        $start = Carbon::parse("{$attendanceDate} {$schedule->start_time}");

        if ($isOnLeave && $attendance->status !== 'on_leave') {
            $attendance->update(['status' => 'on_leave', 'time_in' => null]);
        } elseif (!$isOnLeave && $attendance->time_in && $attendance->time_out) {
            if (!in_array($attendance->status, ['attended', 'early_ended', 'overtime'], true)) {
                $attendance->update(['status' => 'attended']);
            }
        } elseif (!$isOnLeave && (!$attendance->time_in || !$attendance->time_out)) {
            $end = Carbon::parse("{$attendanceDate} {$schedule->end_time}");

            if ($end->lt($start)) {
                $end->addDay();
            }

            $checkoutDeadline = $end->copy()->addMinutes(self::CHECKOUT_GRACE_MINUTES);

            if ($now->greaterThanOrEqualTo($checkoutDeadline) && $attendance->time_in && $attendance->status !== 'overtime') {
                $attendance->update(['status' => 'overtime']);
            } elseif ($now->greaterThanOrEqualTo($checkoutDeadline) && !$attendance->time_in && $attendance->status !== 'absent') {
                $attendance->update(['status' => 'absent']);
            } elseif (
                $attendance->time_in
                && $now->lessThan($checkoutDeadline)
                && !in_array($attendance->status, ['ongoing', 'overtime'], true)
            ) {
                $attendance->update(['status' => 'ongoing']);
            } elseif (
                !$attendance->time_in
                && !$attendance->time_out
                && $attendance->status !== 'waiting'
                && $attendance->status !== 'absent'
            ) {
                $attendance->update(['status' => 'waiting']);
            }
        }

        return $attendance->fresh();
    }

    public static function syncForToday(?Carbon $now = null): int
    {
        $now ??= Carbon::now();
        $attendanceDate = $now->toDateString();
        $today = strtolower($now->format('l'));
        $shortToday = strtolower($now->format('D'));
        $schedules = Schedule::with('User')
            ->where(function ($query) use ($today, $shortToday) {
                $query->whereRaw('LOWER(day) LIKE ?', ['%' . $today . '%'])
                    ->orWhereRaw('LOWER(day) LIKE ?', ['%' . $shortToday . '%']);
            })
            ->get();

        foreach ($schedules as $schedule) {
            self::syncForSchedule($schedule, $attendanceDate, $now);
        }

        return $schedules->count();
    }

    public static function CreateAttendance($userId, $scheduleId, $timeIn, $timeOut, $attendanceDate, $status, $statusOut = null)
    {
        $schedule = Schedule::findOrFail($scheduleId);

        $attendanceDate = $attendanceDate
            ? Carbon::parse($attendanceDate)->toDateString()
            : Carbon::today()->toDateString();

        if ($status !== 'on_leave') {
            if ($timeIn && $timeOut && !in_array($status, ['early_ended', 'overtime'], true)) {
                $status = 'attended';
            } elseif ($timeIn && !$timeOut && $status !== 'overtime') {
                $status = 'ongoing';
            }
        }

        return self::updateOrCreate(
            [
                'user_id' => $userId,
            'college_id' => $schedule->User?->college_id,
                'schedule_id' => $scheduleId,
                'attendance_date' => $attendanceDate,
            ],
            [
                'room_id' => $schedule->room_id,
                'day' => $schedule->day,
                'time_in' => $timeIn,
                'time_out' => $timeOut,
                'status' => $status,
            ]
        );
    }
}
