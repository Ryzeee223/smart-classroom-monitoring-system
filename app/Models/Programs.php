<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Programs extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'programs';

    protected $fillable = [
        'college_id',
        'program_abbr',
        'program_name',
        'description',
    ];

    public function subjects()
    {
        return $this->hasMany(Programs::class);
    }
}
