<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class semyr extends Model
{
    use HasFactory;

    protected $table = 'semyr';

    protected $fillable = [
        'semester',
        'school_year',
    ];
    public function semyr()
    {
        return $this->has(semyr::class);
    }
}
