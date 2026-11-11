<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class room extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'room_name',
        'room_type',
        'status',
        'bldg_id',
    ];

    protected $table = 'room';

    public function building()
    {
        return $this->belongsTo(bldg::class, 'bldg_id');
    }

    // Optional: keep backward compatibility if anything else used the old relation name
    public function roomRelation()
    {
        return $this->building();
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'room_id');
    }

}

