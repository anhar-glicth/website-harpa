<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fundraiser extends Model
{
    protected $fillable = ['user_id', 'name', 'type', 'target', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
