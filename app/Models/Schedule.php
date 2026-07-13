<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

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
        'start_time',
        'end_time',
        
        'Semester',
        'School_year',
    ];

public function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
public function Programs()
{
    return $this->belongsTo(Programs::class, 'program_id', 'id');
}

// Optional: lower-case relationship name (handy in other views)
public function program()
{
    return $this->belongsTo(Programs::class, 'program_id', 'id');
}

public function course()
{
    return $this->belongsTo(course::class, 'course_id');
}
public function room()
{
    return $this->belongsTo(room::class,'room_id');
}
}
