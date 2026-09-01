<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    public $timestamps = false;
    use HasFactory;

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
