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

