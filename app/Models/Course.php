<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class course extends Model
{
    use HasFactory;
protected $table = 'course';
    protected $fillable = [
        'course_code',
        'course_name',
        'description',
        'course_id',
    ];

    public function course()
    {
        return $this->hasMany(course::class);
    }
    public function Programs()
    {
        return $this->belongsTo(Programs::class, 'program_id');
    }
}
