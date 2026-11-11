<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'schedule';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'program_id',
        'room_id',
        'course_id',

        'year_level',
        'section',
        'day',
        'sched_Status',
        'start_time',
        'end_time',
        
        'Semester',
        'School_year',
    ];

public function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

public function programs()
{
    return $this->belongsTo(Programs::class, 'program_id', 'id');
}

public function Program()
{
    return $this->belongsTo(Programs::class, 'program_id', 'id');
}

public function course()
{
    return $this->belongsTo(Course::class, 'course_id');
}
public function room()
{
    return $this->belongsTo(room::class,'room_id');
}
}
