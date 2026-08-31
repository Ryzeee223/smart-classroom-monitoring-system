<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class report extends Model
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

    public static function CreateAttendance($userId, $scheduleId, $timeIn, $timeOut, $attendanceDate, $statusIn, $statusOut)
    {
        $schedule = Schedule::findOrFail($scheduleId);

        $attendanceDate = $attendanceDate instanceof \DateTimeInterface
            ? \Illuminate\Support\Carbon::parse($attendanceDate)->toDateString()
            : \Illuminate\Support\Carbon::parse((string) $attendanceDate)->toDateString();

        return self::create([
            'user_id' => $userId,
            'schedule_id' => $scheduleId,
            'room_id' => $schedule->room_id,
            'day' => $schedule->day,
            'time_in' => $timeIn,
            'time_out' => $timeOut,
            'attendance_date' => $attendanceDate,
            'status_in' => $statusIn,
            'status_out' => $statusOut,
        ]);
    }
}
