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

public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
