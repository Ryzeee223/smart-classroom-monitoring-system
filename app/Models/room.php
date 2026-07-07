<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_name',
        'room_type',
    ];

    protected $table = 'room';

    public function room()
    {
        return $this->belongsTo(bldg::class, 'bldg_id');
    }
}



