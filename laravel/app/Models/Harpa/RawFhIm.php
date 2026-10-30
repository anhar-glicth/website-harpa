<?php

namespace App\Models\Harpa;

use Illuminate\Database\Eloquent\Model;

class RawFhIm extends Model
{
    protected $table = 'raw_fh_im';

    protected $fillable = [
        'sumber',
        'invoice_id',
        'data_json',
        'batch_tarik',
        'tgl_tempel',
    ];

    protected $casts = [
        'data_json'  => 'array',
        'tgl_tempel' => 'datetime',
    ];
}

