<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class programs extends Model
{
    use HasFactory;

    protected $table = 'programs';

    protected $fillable = [
        'college_id',
        'program_abbr',
        'program_name',
        'description',
    ];

    public function subjects()
    {
        return $this->hasMany(programs::class);
    }
}
