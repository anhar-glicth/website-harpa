<?php

namespace App\Models\Harpa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterNomor extends Model
{
    protected $table = 'master_nomor';
    protected $primaryKey = 'no_hp';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'no_hp',
        'donatur_id',
        'status_nomor',
        'wa_aktif',
        'tgl_catat',
        'catatan',
    ];

    protected $casts = [
        'wa_aktif'  => 'boolean',
        'tgl_catat' => 'date',
    ];

    /**
     * Relasi ke master orang / pemilik nomor.
     */
    public function donatur(): BelongsTo
    {
        return $this->belongsTo(MasterDonatur::class, 'donatur_id', 'donatur_id');
    }

    /**
     * Relasi ke riwayat blast yang pernah dikirim ke nomor ini.
     */
    public function blastHistory(): HasMany
    {
        return $this->hasMany(RawBlast::class, 'receiver_number', 'no_hp');
    }

    /**
     * Scope nomor yang WA-nya masih aktif (bukan nomor mati hasil WBS).
     */
    public function scopeWaAktif($query)
    {
        return $query->where('wa_aktif', true);
    }

    /**
     * Fungsi normalisasi nomor HP sesuai aturan Harpa:
     * - Buang semua karakter selain angka
     * - Awalan 0 diganti 62
     * - Awalan 8 diberi 62
     * - Nomor valid bila diawali 628 dan panjangnya 11–14 digit.
     */
    public static function normalize(string $rawNumber): array
    {
        // 1. Bersihkan karakter selain angka
        $clean = preg_replace('/[^0-9]/', '', (string)$rawNumber);

        if (empty($clean)) {
            return [
                'is_valid' => false,
                'number'   => null,
                'error'    => 'Nomor kosong',
            ];
        }

        // 2. Koreksi awalan
        if (str_starts_with($clean, '0')) {
            $clean = '62' . substr($clean, 1);
        } elseif (str_starts_with($clean, '8')) {
            $clean = '62' . $clean;
        }

        // 3. Validasi awalan 628 dan panjang 11-14 digit
        $len = strlen($clean);
        $isValid = (str_starts_with($clean, '628') && $len >= 11 && $len <= 14);

        return [
            'is_valid' => $isValid,
            'number'   => $clean,
            'error'    => $isValid ? null : ($len < 11 || $len > 14 ? 'Panjang digit di luar 11-14' : 'Bukan nomor 628'),
        ];
    }
}

