<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PicTarget extends Model
{
    protected $fillable = ['pic_id', 'month_period', 'target_amount'];

    protected $casts = [
        'month_period' => 'date',
    ];

    public function pic()
    {
        return $this->belongsTo(User::class, 'pic_id');
    }
}
