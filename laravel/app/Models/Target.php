<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    protected $fillable = ['period', 'program_id', 'amount', 'achievement'];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'program_id');
    }
}
