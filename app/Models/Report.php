<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Report extends Model
{
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

            $roomIsOccupied = self::where('room_id', $attendance->room_id)
                ->whereNotNull('time_in')
                ->whereNull('time_out')
                ->exists();

            room::whereKey($attendance->room_id)->update([
                'status' => $roomIsOccupied ? 'occupied' : 'vacant',
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
        } elseif (!$isOnLeave
            && $attendance->status === 'waiting'
            && !$attendance->time_in
            && $now->gte($start->copy()->addMinutes(30))) {
            $attendance->update(['status' => 'absent']);
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

