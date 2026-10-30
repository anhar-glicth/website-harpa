<?php

namespace App\Models\Harpa;

use Illuminate\Database\Eloquent\Model;

class KontrolBlast extends Model
{
    protected $table = 'kontrol_blast';

    protected $fillable = [
        'tgl_blast',
        'tgl_tarik',
        'batch_label',
        'jml_baris_export',
        'jml_nomor_unik',
        'ditarik_oleh',
        'catatan',
    ];

    protected $casts = [
        'tgl_blast'        => 'date',
        'tgl_tarik'        => 'datetime',
        'jml_baris_export' => 'integer',
        'jml_nomor_unik'   => 'integer',
    ];
}

