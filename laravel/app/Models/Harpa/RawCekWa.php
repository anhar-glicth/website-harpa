<?php

namespace App\Models\Harpa;

use Illuminate\Database\Eloquent\Model;

class RawCekWa extends Model
{
    protected $table = 'raw_cek_wa';

    protected $fillable = [
        'no_hp',
        'is_wa_aktif',
        'tgl_cek',
        'catatan',
        'batch_tarik',
    ];

    protected $casts = [
        'is_wa_aktif' => 'boolean',
        'tgl_cek'     => 'date',
    ];
}

