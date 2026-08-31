<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class course extends Model
{
    use HasFactory;
protected $table = 'courses';
    protected $fillable = [
        'college_id',
        'course_code',
        'course_name',
        'description',
    ];

    public function course()
    {
        return $this->hasMany(course::class);
    }
    public function college()
    {
        return $this->belongsTo(college::class, 'college_id');
    }
}
