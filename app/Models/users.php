<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class users extends model
{
protected $table = 'users';

    protected $hidden = ['password'];

    protected $casts = [
        'password' => 'hashed',
    ];

    protected $fillable = [
        'first_name',
        'last_name', 
        'employee_ID',
        'role',
        'email',
        'password',
        'course',
        'year_level',
        'profile_picture',
        'RFID_code',
        'acc_status',
        'status',
    ];

    protected $course = '';

    public function setCourseAttribute()
    {
        $this->attributes['course'] = '';
    }

}

