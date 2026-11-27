<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('harpa:sync-donors', function () {
    $this->info('Menyinkronkan data donatur dan transaksi dari Sistem Data Harpa ke List Donatur...');
    $dataEngine = app(\App\Services\Harpa\HarpaDataEngineService::class);
    $res = $dataEngine->syncAllHarpaToWeb();
    $this->info("Berhasil! {$res['synced_donors']} donatur dan {$res['synced_donations']} transaksi berhasil dihubungkan ke List Donatur.");
})->purpose('Sinkronkan data donatur dan transaksi dari Sistem Data Harpa ke List Donatur');
