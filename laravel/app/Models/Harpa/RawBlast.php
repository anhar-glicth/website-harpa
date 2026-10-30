<?php

namespace App\Models\Harpa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RawBlast extends Model
{
    protected $table = 'raw_blast';

    protected $fillable = [
        'receiver_number',
        'broadcast_id',
        'message_id',
        'status',
        'timestamp_blast',
        'batch_tarik',
        'raw_json',
    ];

    protected $casts = [
        'timestamp_blast' => 'datetime',
        'raw_json'        => 'array',
    ];

    public function nomor(): BelongsTo
    {
        return $this->belongsTo(MasterNomor::class, 'receiver_number', 'no_hp');
    }

    /**
     * Normalisasi teks STATUS dari Everpro:
     * - mengandung read -> READ
     * - mengandung deliver -> DELIVERED
     * - mengandung succ -> SUCCESS
     * - mengandung fail -> FAILED
     * - selain itu / kosong -> LAINNYA
     */
    public static function normalizeStatus(?string $rawStatus): string
    {
        $lower = strtolower(trim((string)$rawStatus));

        if (str_contains($lower, 'read')) {
            return 'READ';
        }
        if (str_contains($lower, 'deliver')) {
            return 'DELIVERED';
        }
        if (str_contains($lower, 'succ')) {
            return 'SUCCESS';
        }
        if (str_contains($lower, 'fail')) {
            return 'FAILED';
        }

        return 'LAINNYA';
    }
}

