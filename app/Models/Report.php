<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Report extends Model
{
    protected $table = 'attendance';

    protected $fillable = [
        'user_id',
        'schedule_id',
        'room_id',
        'day',
        'time_in',
        'time_out',
        'attendance_date',
        'status',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    public function user()
    {
        return $this->belongsTo(users::class, 'user_id');
    }

    public static function syncForSchedule(Schedule $schedule, string $attendanceDate, Carbon $now): self
    {
        $attendance = self::firstOrCreate(
            [
                'user_id' => $schedule->user_id,
                'schedule_id' => $schedule->id,
                'attendance_date' => $attendanceDate,
            ],
            [
                'room_id' => $schedule->room_id,
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
            && $now->gte($start->copy()->addMinutes(15))) {
            $attendance->update(['status' => 'absent']);
        }

        return $attendance->fresh();
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

