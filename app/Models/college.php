<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class College extends Model
{
    public $timestamps = false;
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'college_name',
        'abbreviation',
        'description',
        
    ];

    protected $table = 'college';


    public function course()
    {
        return $this->hasMany(Course::class);
    }
}
