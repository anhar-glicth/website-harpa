<?php

namespace App\Models\Harpa;

use Illuminate\Database\Eloquent\Model;

class RawWa extends Model
{
    protected $table = 'raw_wa';

    protected $fillable = [
        'transaksi_id',
        'data_json',
        'batch_tarik',
        'tgl_tempel',
    ];

    protected $casts = [
        'data_json'  => 'array',
        'tgl_tempel' => 'datetime',
    ];
}

