<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
protected $table = 'device';
    protected $fillable = [
        'room_id',
        'mac_address',
        'ip_address',
        'status',
        'last_seen',
    ];
    protected $casts = ['last_seen' => 'datetime'];
    
    public function Device()
    {
        return $this->hasMany(Device::class);
    }
    public function college()
    {
        return $this->belongsTo(room::class, 'room_id');
    }}
