<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bldg extends Model
{
    use HasFactory;

    protected $fillable =[
        'bldg_abbr',
        'bldg_name',
        
    ];
    protected $table = 'building';

    public function building()
    {
        return $this->hasMany(bldg::class);
    }
}
