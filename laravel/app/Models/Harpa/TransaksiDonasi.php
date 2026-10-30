<?php

namespace App\Models\Harpa;

use App\Models\Campaign;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiDonasi extends Model
{
    protected $table = 'transaksi_donasi';
    protected $primaryKey = 'donasi_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'donasi_id',
        'donatur_id',
        'no_hp_input',
        'campaign_id',
        'campaign_title_raw',
        'tanggal',
        'jam',
        'nominal',
        'kode_unik',
        'total',
        'via_bayar',
        'bank_id',
        'status_bayar',
        'channel_donasi',
        'channel_konversi',
        'pic_transaksi',
        'utm_source',
        'utm_campaign',
        'sumber_data',
        'ref_asal',
        'batch_tarik',
        'tgl_input',
    ];

    protected $casts = [
        'tanggal'   => 'date',
        'nominal'   => 'decimal:2',
        'total'     => 'decimal:2',
        'kode_unik' => 'integer',
        'tgl_input' => 'datetime',
    ];

    public function donatur(): BelongsTo
    {
        return $this->belongsTo(MasterDonatur::class, 'donatur_id', 'donatur_id');
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'campaign_id', 'id');
    }

    public function scopeSuccess($query)
    {
        return $query->where('status_bayar', 'success');
    }

    public function scopeWaiting($query)
    {
        return $query->where('status_bayar', 'waiting');
    }

    /**
     * Generate donasi_id berurutan per sumber (FH-000001, IM-000001, WA-000001).
     */
    public static function generateNextId(string $source): string
    {
        $prefix = strtoupper(trim($source)) . '-';

        $lastRecord = static::where('donasi_id', 'like', $prefix . '%')
            ->orderByRaw("CAST(SUBSTRING(donasi_id, " . (strlen($prefix) + 1) . ") AS UNSIGNED) DESC")
            ->first();

        if ($lastRecord) {
            $lastNumber = (int) substr($lastRecord->donasi_id, strlen($prefix));
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix . str_pad((string) $nextNumber, 6, '0', STR_PAD_LEFT);
    }
}

