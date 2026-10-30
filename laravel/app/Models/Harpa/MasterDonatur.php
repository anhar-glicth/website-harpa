<?php

namespace App\Models\Harpa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class MasterDonatur extends Model
{
    protected $table = 'master_donatur';
    protected $primaryKey = 'donatur_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'donatur_id',
        'no_hp',
        'nama_lengkap',
        'nama_panggilan',
        'panggilan',
        'jenis_kelamin',
        'email',
        'kota',
        'provinsi',
        'profil_donatur',
        'tgl_registrasi',
        'sumber_informasi',
        'channel_akuisisi',
        'pic_prospek',
        'kantor_layanan',
        'is_aktif',
        'catatan',
        'tgl_input',
    ];

    protected $casts = [
        'is_aktif'       => 'boolean',
        'tgl_registrasi' => 'date',
        'tgl_input'      => 'datetime',
    ];

    /**
     * Relasi ke nomor-nomor HP milik donatur ini.
     * Satu orang boleh punya beberapa nomor.
     */
    public function nomor(): HasMany
    {
        return $this->hasMany(MasterNomor::class, 'donatur_id', 'donatur_id');
    }

    /**
     * Relasi ke semua transaksi donasi milik orang ini.
     */
    public function transaksi(): HasMany
    {
        return $this->hasMany(TransaksiDonasi::class, 'donatur_id', 'donatur_id');
    }

    /**
     * Transaksi sukses saja.
     */
    public function transaksiSukses(): HasMany
    {
        return $this->hasMany(TransaksiDonasi::class, 'donatur_id', 'donatur_id')
            ->where('status_bayar', 'success');
    }

    /**
     * Generate ID donatur berikutnya berformat DNR + 6 digit (contoh: DNR013115).
     * Berurutan dan tidak pernah dipakai ulang.
     */
    public static function generateNextId(): string
    {
        $lastDonatur = static::where('donatur_id', 'like', 'DNR%')
            ->orderByRaw("CAST(SUBSTRING(donatur_id, 4) AS UNSIGNED) DESC")
            ->first();

        if ($lastDonatur) {
            $lastNumber = (int) substr($lastDonatur->donatur_id, 3);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return 'DNR' . str_pad((string) $nextNumber, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Sumbu 1: Kategori Donatur (LOYAL >= 3x, AKTIF >= 1x)
     */
    public function getKategoriAttribute(): string
    {
        $count = $this->transaksiSukses()->count();
        if ($count >= 3) {
            return 'LOYAL';
        } elseif ($count >= 1) {
            return 'AKTIF';
        }
        return 'BARU';
    }

    /**
     * Sumbu 2: Badge Donatur (PLATINUM >= 10jt, GOLD >= 1jt, SILVER >= 1)
     */
    public function getBadgeAttribute(): string
    {
        $total = (float) $this->transaksiSukses()->sum('nominal');
        if ($total >= 10000000) {
            return 'PLATINUM';
        } elseif ($total >= 1000000) {
            return 'GOLD';
        } elseif ($total > 0) {
            return 'SILVER';
        }
        return 'NONE';
    }
}

