<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class programs extends Model
{
    use HasFactory;

    protected $fillable = [
        'college_id',
        'Program_abbr',
        'Program_name',
        'description',
    ];

    protected $table = 'Programs';

    public function subjects()
    {
        return $this->hasMany(Programs::class);
    }
    
}
