<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProspectResponse extends Model
{
    protected $fillable = ['prospect_id', 'response', 'status'];

    public function prospect()
    {
        return $this->belongsTo(Prospect::class);
    }
}
