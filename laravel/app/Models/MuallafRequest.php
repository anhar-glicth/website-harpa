<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MuallafRequest extends Model
{
    protected $fillable = ['name', 'email', 'address', 'desired_date', 'location', 'status'];

    protected $casts = [
        'desired_date' => 'date',
    ];
}
