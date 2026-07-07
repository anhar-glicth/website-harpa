<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prospect extends Model
{
    protected $fillable = ['name', 'phone', 'email'];

    public function responses()
    {
        return $this->hasMany(ProspectResponse::class);
    }
}
