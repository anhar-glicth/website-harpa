<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterTarget extends Model
{
    use HasFactory;

    protected $table = 'master_targets';

    protected $fillable = ['target_code', 'target_date', 'target_amount', 'notes'];

    protected $casts = [
        'target_date'   => 'date',
        'target_amount' => 'decimal:2',
    ];
}
