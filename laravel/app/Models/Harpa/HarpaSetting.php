<?php

namespace App\Models\Harpa;

use Illuminate\Database\Eloquent\Model;

class HarpaSetting extends Model
{
    protected $table = 'harpa_settings';

    protected $fillable = [
        'key',
        'value',
        'description',
    ];

    /**
     * Ambil nilai pengaturan dengan nilai default jika belum ada.
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Simpan atau perbarui nilai pengaturan.
     */
    public static function set(string $key, $value, ?string $description = null): self
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => (string) $value, 'description' => $description]
        );
    }

    /**
     * Inisialisasi ambang batas bawaan sesuai spesifikasi Harpa Data Engine.
     */
    public static function seedDefaults(): void
    {
        $defaults = [
            'threshold_failed_beruntun'           => ['2', 'Ambang FAILED berturut-turut untuk masuk ARSIP Nomor Bermasalah'],
            'threshold_total_blast_read0'         => ['3', 'Ambang total blast tanpa pernah dibaca (read=0) untuk ARSIP Tidak Pernah Baca'],
            'threshold_blast_sejak_read'          => ['3', 'Ambang pesan setelah pembacaan terakhir untuk ARSIP Berhenti Baca'],
            'payday_start_day'                    => ['1', 'Tanggal awal pengiriman broadcast Payday'],
            'payday_end_day'                      => ['5', 'Tanggal akhir pengiriman broadcast Payday'],
            'perkenalan_grace_days'               => ['28', 'Jumlah hari nomor masuk ke KIRIM_JUMAT_BARU setelah perkenalan'],
            'blast_start_date'                    => ['2026-06-12', 'Tanggal data blast paling awal yang sah dalam sistem'],
            'threshold_champion_min_freq'        => ['3', 'Minimal frekuensi donasi segmen CHAMPION'],
            'threshold_champion_min_amount'      => ['1000000', 'Minimal total donasi nominal CHAMPION (Rp)'],
            'threshold_days_champion_vs_at_risk' => ['90', 'Batas hari terakhir donasi CHAMPION vs HIGH VALUE AT RISK'],
            'threshold_days_fresh'               => ['30', 'Batas hari donasi pertama/terakhir untuk FRESH'],
            'threshold_days_potential_vs_dormant'=> ['180', 'Batas hari terakhir donasi POTENTIAL vs DORMANT'],
            'window_rfm_months'                  => ['12', 'Jendela periode analisis RFM (bulan)'],
            'donasi_terakhir_max_bulan'          => ['0', 'Filter opsional batas donasi terakhir AKTIF Donatur (0 = semua donasi success sesuai SOP)'],
        ];

        foreach ($defaults as $key => [$val, $desc]) {
            if (!static::where('key', $key)->exists()) {
                static::create([
                    'key'         => $key,
                    'value'       => $val,
                    'description' => $desc,
                ]);
            }
        }
    }
}

