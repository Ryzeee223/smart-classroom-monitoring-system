<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $table = 'requests';

  
    protected $fillable = [
        'user_id',
        'letter',
        'reason',
        'status',
    ];

   
    protected $casts = [
        'status' => 'string',
    ];

    
    public function user()
    {
        return $this->belongsTo(users::class, 'user_id', 'id');
    }
}
