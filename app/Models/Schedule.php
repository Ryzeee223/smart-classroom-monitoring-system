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
        'id',
        'user_id',
        'Day',
        'Time',
        'Subject',
        'Room',
        'Semester',
        'School_year',
        'course',
        'year_level',
        'section'
    ];

public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
