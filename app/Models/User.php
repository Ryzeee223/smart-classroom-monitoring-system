<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\college;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'Academic_rank',
        'employee_ID',
        'email',
        'password',
        'role',
        'college_id',
        'Advisory_class',
        'class_count',
        'acc_status',
        'profile_picture_data',
        'profile_picture_mime',
        'RFID_code',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function College()
    {
        return $this->belongsTo(college::class, 'college_id');
    }
}
