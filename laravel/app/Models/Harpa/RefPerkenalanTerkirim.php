<?php

namespace App\Models\Harpa;

use Illuminate\Database\Eloquent\Model;

class RefPerkenalanTerkirim extends Model
{
    protected $table = 'ref_perkenalan_terkirim';

    protected $fillable = [
        'no_hp',
        'tgl_dikirim',
        'broadcast_id',
        'catatan',
    ];

    protected $casts = [
        'tgl_dikirim' => 'date',
    ];
}

