<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prayer extends Model
{
    protected $fillable = ['user_id', 'name', 'message', 'is_anonymous', 'aamiin_count'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
