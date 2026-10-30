<?php

namespace App\Models\Harpa;

use Illuminate\Database\Eloquent\Model;

class KontrolTarik extends Model
{
    protected $table = 'kontrol_tarik';

    protected $fillable = [
        'sumber',
        'periode',
        'tgl_tarik',
        'jml_baris',
        'id_pertama',
        'id_terakhir',
        'ditarik_oleh',
        'catatan',
    ];

    protected $casts = [
        'tgl_tarik' => 'datetime',
        'jml_baris' => 'integer',
    ];
}

