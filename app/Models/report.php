<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class report extends Model
{
    protected $table = 'attendance';

    protected $fillable = [
        'user_id',
        'schedule_id',
        'time_in',
        'time_out',
        'attendance_date',
        'status_in',
        'status_out',
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
        return self::create([
            'user_id' => $userId,
            'schedule_id' => $scheduleId,
            'time_in' => $timeIn,
            'time_out' => $timeOut,
            'attendance_date' => $attendanceDate,
            'status_in' => $statusIn,
            'status_out' => $statusOut,
        ]);
    }
}
