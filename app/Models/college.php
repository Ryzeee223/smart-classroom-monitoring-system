<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class college extends Model
{
    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'college_name',
        'abbreviation',
        'description',
        'user_id',
    ];

    protected $table = 'college';


    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}
