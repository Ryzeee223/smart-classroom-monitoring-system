<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'courses';

    protected $fillable = [
        'college_id',
        'course_code',
        'course_name',
        'unit'
    ];

    public function course()
    {
        return $this->hasMany(Course::class);
    }

    public function college()
    {
        return $this->belongsTo(College, 'college_id');
    }
}
