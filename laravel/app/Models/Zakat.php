<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zakat extends Model
{
    protected $fillable = ['user_id', 'zakat_type', 'amount', 'payment_status', 'admin_note'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
