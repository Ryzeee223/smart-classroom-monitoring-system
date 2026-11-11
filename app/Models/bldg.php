<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class bldg extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable =[
        'bldg_abbr',
        'bldg_name',
        'college_id',
        
    ];
    protected $table = 'building';

   

}
