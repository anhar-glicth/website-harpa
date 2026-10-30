<?php

namespace App\Services\Harpa;

use App\Models\Campaign;
use App\Models\Harpa\HarpaSetting;
use App\Models\Harpa\KontrolBlast;
use App\Models\Harpa\KontrolTarik;
use App\Models\Harpa\MasterDonatur;
use App\Models\Harpa\MasterNomor;
use App\Models\Harpa\RawBlast;
use App\Models\Harpa\RawCekWa;
use App\Models\Harpa\RawFhIm;
use App\Models\Harpa\RawWa;
use App\Models\Harpa\RefCampaignAlias;
use App\Models\Harpa\RefPerkenalanTerkirim;
use App\Models\Harpa\TransaksiDonasi;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HarpaDataEngineService
{
    /**
     * Membaca isi file (CSV / XLSX) menjadi array baris [headers, dataRows].
     */
    public function readFile(string $filePath, ?string $extension = null): array
    {
        $ext = strtolower($extension ?: pathinfo($filePath, PATHINFO_EXTENSION));

        if (in_array($ext, ['xlsx', 'xls'])) {
            $xlsxClass = 'Shuchkin\\SimpleXLSX';
            if (!class_exists($xlsxClass)) {
                $file = base_path('vendor/shuchkin/simplexlsx/src/SimpleXLSX.php');
                if (file_exists($file)) {
                    require_once $file;
                }
            }

            if (class_exists($xlsxClass)) {
                $xlsx = $xlsxClass::parse($filePath);
                if ($xlsx) {
                    $rows = $xlsx->rows();
                    if (!empty($rows)) {
                        $headers = array_shift($rows);
                        return [
                            'headers' => array_map('trim', (array) $headers),
                            'rows'    => $rows,
                        ];
                    }
                }
            }
        }

        // Fallback pembacaan CSV/TSV/TXT dengan deteksi delimiter otomatis
        $handle = fopen($filePath, 'r');
        if (!$handle) {
            throw new \Exception("Gagal membuka file: {$filePath}");
        }

        // Hapus BOM jika ada
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        // Deteksi delimiter: koma, titik koma, atau tab
        $firstLine = fgets($handle);
        rewind($handle);
        if ($bom === "\xEF\xBB\xBF") {
            fread($handle, 3);
        }

        $delimiters = [',', ';', "\t", '|'];
        $bestDelim = ',';
        $maxCount = 0;
        foreach ($delimiters as $d) {
            $count = substr_count($firstLine, $d);
            if ($count > $maxCount) {
                $maxCount = $count;
                $bestDelim = $d;
            }
        }

        $headers = fgetcsv($handle, 0, $bestDelim);
        $rows = [];
        while (($row = fgetcsv($handle, 0, $bestDelim)) !== false) {
            if (!empty(array_filter($row, fn($v) => trim((string)$v) !== ''))) {
                $rows[] = $row;
            }
        }
        fclose($handle);

        return [
            'headers' => array_map('trim', (array) ($headers ?: [])),
            'rows'    => $rows,
        ];
    }

    /**
     * Parsing tanggal seragam dari format web ("Sep 05, 2026 - 06:08") atau ISO ("2026-09-05 06:08").
     */
    public function parseDateString(?string $dateStr): ?Carbon
    {
        if (empty(trim((string)$dateStr))) {
            return null;
        }

        $str = trim((string)$dateStr);
        // Hapus pemisah strip jam contoh "Sep 05, 2026 - 06:08" -> "Sep 05, 2026 06:08"
        $strClean = preg_replace('/\s*-\s*/', ' ', $str);

        $formats = [
            'M d, Y H:i',
            'M d, Y H:i:s',
            'Y-m-d H:i:s',
            'Y-m-d H:i',
            'Y-m-d',
            'd/m/Y H:i:s',
            'd/m/Y H:i',
            'd/m/Y',
            'd-m-Y H:i',
            'd-m-Y',
        ];

        foreach ($formats as $fmt) {
            try {
                $dt = Carbon::createFromFormat($fmt, $strClean);
                if ($dt !== false) {
                    return $dt;
                }
            } catch (\Throwable $e) {
                // Lanjut ke format berikutnya
            }
        }

        try {
            return Carbon::parse($strClean);
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * =========================================================================
     * ALUR A: MASUK DATA DONASI (FH, IM, WA) & PAPAN KERJA (GERBANG BERHENTI)
     * =========================================================================
     */
    public function processDonationImport(
        string $filePath,
        string $source, // 'FH', 'IM', 'WA'
        string $periode, // YYYY-MM
        string $userName,
        bool $commitDirectly = false,
        ?string $extension = null
    ): array {
        $source = strtoupper(trim($source));
        $fileData = $this->readFile($filePath, $extension);
        $headers = $fileData['headers'];
        $rows = $fileData['rows'];

        // Cek duplikasi kontrol tarik periode yang sama untuk sumber ini
        $existingKontrol = KontrolTarik::where('sumber', $source)
            ->where('periode', $periode)
            ->first();

        if ($existingKontrol && !$commitDirectly) {
            return [
                'status'  => 'STOP',
                'message' => "Periode {$periode} untuk sumber {$source} sudah pernah ditarik pada {$existingKontrol->tgl_tarik}. Impor dibatalkan untuk mencegah tumpang tindih.",
                'kontrol' => $existingKontrol,
            ];
        }

        // Peta index kolom berdasarkan header
        $headerMap = [];
        foreach ($headers as $idx => $name) {
            $key = strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', str_replace(' ', '_', $name)));
            $headerMap[$key] = $idx;
        }

        $batchTarik = strtolower($source) . '-' . $periode . '-' . date('YmdHis');
        $parsedRows = [];
        $unregisteredNumbers = [];
        $unmappedCampaigns = [];
        $duplicateInvoices = [];
        $dateErrors = [];
        $tanpaNomorCount = 0;
        $firstId = null;
        $lastId = null;

        foreach ($rows as $rowIndex => $r) {
            // Deteksi Invoice ID / Transaksi ID
            $invId = $r[$headerMap['invoice_id'] ?? $headerMap['invoice'] ?? $headerMap['id_transaksi'] ?? $headerMap['transaksi_id'] ?? 0] ?? null;
            $invId = trim((string)$invId);

            if (empty($invId)) {
                continue;
            }

            if (!$firstId) $firstId = $invId;
            $lastId = $invId;

            // Cek duplikasi ID transaksi
            if ($source === 'WA') {
                if (RawWa::where('transaksi_id', $invId)->exists()) {
                    $duplicateInvoices[] = $invId;
                    continue;
                }
            } else {
                if (RawFhIm::where('sumber', $source)->where('invoice_id', $invId)->exists()) {
                    $duplicateInvoices[] = $invId;
                    continue;
                }
            }

            // Normalisasi nomor HP
            $rawPhone = $r[$headerMap['phone'] ?? $headerMap['no_hp'] ?? $headerMap['telepon'] ?? $headerMap['whatsapp'] ?? 4] ?? '';
            $normPhone = MasterNomor::normalize($rawPhone);
            $cleanPhone = $normPhone['number'];
            $isPhoneValid = $normPhone['is_valid'];

            if (!$isPhoneValid) {
                $tanpaNomorCount++;
            }

            // Normalisasi Tanggal
            $rawDate = $r[$headerMap['date'] ?? $headerMap['tanggal'] ?? $headerMap['waktu'] ?? $headerMap['created_at'] ?? 1] ?? '';
            $parsedDate = $this->parseDateString($rawDate);
            if (!$parsedDate) {
                $dateErrors[] = [
                    'row'   => $rowIndex + 2,
                    'raw'   => $rawDate,
                    'invId' => $invId,
                ];
            }

            // Normalisasi Judul Campaign
            $rawCampaign = $r[$headerMap['campaign'] ?? $headerMap['judul_campaign'] ?? $headerMap['program'] ?? $headerMap['judul_program'] ?? 2] ?? '';
            $rawCampaign = trim((string)$rawCampaign);

            $alias = RefCampaignAlias::resolveCampaign($rawCampaign);
            $resolvedCampaignId = $alias?->campaign_id;

            // Jika belum ada alias, coba cari berdasarkan judul di tabel campaigns
            if (!$resolvedCampaignId && !empty($rawCampaign)) {
                $directMatch = Campaign::where('title', 'like', '%' . $rawCampaign . '%')->first();
                if ($directMatch) {
                    $resolvedCampaignId = $directMatch->id;
                } else {
                    if (!in_array($rawCampaign, $unmappedCampaigns)) {
                        $unmappedCampaigns[] = $rawCampaign;
                    }
                }
            }

            // Cek Kepemilikan Orang di master_nomor
            $donaturId = null;
            if ($isPhoneValid) {
                $existingNomor = MasterNomor::find($cleanPhone);
                if ($existingNomor) {
                    $donaturId = $existingNomor->donatur_id;
                } else {
                    if (!isset($unregisteredNumbers[$cleanPhone])) {
                        $rawName = $r[$headerMap['name'] ?? $headerMap['nama'] ?? $headerMap['nama_lengkap'] ?? $headerMap['donatur'] ?? 3] ?? 'Donatur';
                        $unregisteredNumbers[$cleanPhone] = trim((string)$rawName);
                    }
                }
            } else {
                $donaturId = 'TANPA-NOMOR';
            }

            // Nominal dan Kode Unik
            $rawAmount = $r[$headerMap['amount'] ?? $headerMap['nominal'] ?? $headerMap['donasi'] ?? 5] ?? 0;
            $rawUnique = $r[$headerMap['unique_code'] ?? $headerMap['kode_unik'] ?? 6] ?? 0;
            $amount = (float) preg_replace('/[^0-9.]/', '', (string)$rawAmount);
            $uniqueCode = (int) preg_replace('/[^0-9]/', '', (string)$rawUnique);
            $total = $amount + $uniqueCode;

            // Status bayar
            $rawStatus = strtolower(trim((string)($r[$headerMap['status'] ?? $headerMap['payment_status'] ?? 7] ?? 'success')));
            $statusBayar = str_contains($rawStatus, 'wait') || str_contains($rawStatus, 'pend') ? 'waiting' : 'success';

            // Data parsed row
            $parsedRows[] = [
                'ref_asal'            => $invId,
                'no_hp_clean'         => $cleanPhone,
                'is_phone_valid'      => $isPhoneValid,
                'donatur_id'          => $donaturId,
                'nama'                => $r[$headerMap['name'] ?? $headerMap['nama'] ?? 3] ?? 'Donatur',
                'campaign_title_raw'  => $rawCampaign,
                'campaign_id'         => $resolvedCampaignId,
                'tanggal'             => $parsedDate ? $parsedDate->toDateString() : null,
                'jam'                 => $parsedDate ? $parsedDate->toTimeString() : null,
                'nominal'             => $amount,
                'kode_unik'           => $uniqueCode,
                'total'               => $total,
                'status_bayar'        => $statusBayar,
                'channel_donasi'      => $source === 'WA' ? 'wa' : ($source === 'IM' ? 'web_im' : 'web_fh'),
                'channel_konversi'    => $source === 'WA' ? 'everpro' : 'organik',
                'via_bayar'           => $r[$headerMap['via_bayar'] ?? $headerMap['payment_method'] ?? $headerMap['bank'] ?? 8] ?? null,
                'raw_row'             => $r,
            ];
        }

        // EVALUASI GERBANG BERHENTI (PAPAN KERJA)
        $hasDateErrors = !empty($dateErrors);
        $hasUnmappedCampaigns = !empty($unmappedCampaigns);
        $hasUnregisteredNumbers = !empty($unregisteredNumbers);

        $isReady = (!$hasDateErrors && !$hasUnmappedCampaigns && !$hasUnregisteredNumbers);

        // Jika dipanggil untuk commit langsung (semua lolos atau user sudah menyetujui Papan Kerja)
        if ($commitDirectly && !$hasDateErrors) {
            DB::beginTransaction();
            try {
                // 1. Daftarkan nomor-nomor baru sekaligus donatur_id baru jika belum ada
                foreach ($unregisteredNumbers as $phone => $name) {
                    $nextDnr = MasterDonatur::generateNextId();
                    MasterDonatur::create([
                        'donatur_id'     => $nextDnr,
                        'no_hp'          => $phone,
                        'nama_lengkap'   => $name,
                        'is_aktif'       => true,
                        'tgl_registrasi' => now()->toDateString(),
                        'tgl_input'      => now(),
                    ]);

                    MasterNomor::create([
                        'no_hp'        => $phone,
                        'donatur_id'   => $nextDnr,
                        'status_nomor' => 'aktif',
                        'wa_aktif'     => true,
                        'tgl_catat'    => now()->toDateString(),
                    ]);
                }

                // 2. Simpan Data Mentah (raw_)
                foreach ($parsedRows as $p) {
                    if ($source === 'WA') {
                        RawWa::create([
                            'transaksi_id' => $p['ref_asal'],
                            'data_json'    => $p['raw_row'],
                            'batch_tarik'  => $batchTarik,
                            'tgl_tempel'   => now(),
                        ]);
                    } else {
                        RawFhIm::create([
                            'sumber'      => $source,
                            'invoice_id'  => $p['ref_asal'],
                            'data_json'   => $p['raw_row'],
                            'batch_tarik' => $batchTarik,
                            'tgl_tempel'  => now(),
                        ]);
                    }

                    // Tentukan donatur_id final
                    $finalDonaturId = $p['donatur_id'];
                    if (!$finalDonaturId || $finalDonaturId === 'PENDING') {
                        $nm = MasterNomor::find($p['no_hp_clean']);
                        $finalDonaturId = $nm ? $nm->donatur_id : ($p['is_phone_valid'] ? 'DNR-ERR' : 'TANPA-NOMOR');
                    }

                    // Generate donasi_id
                    $donasiId = TransaksiDonasi::generateNextId($source);

                    // Simpan transaksi
                    TransaksiDonasi::create([
                        'donasi_id'          => $donasiId,
                        'donatur_id'         => $finalDonaturId,
                        'no_hp_input'        => $p['no_hp_clean'],
                        'campaign_id'        => $p['campaign_id'],
                        'campaign_title_raw' => $p['campaign_title_raw'],
                        'tanggal'            => $p['tanggal'] ?: now()->toDateString(),
                        'jam'                => $p['jam'] ?: now()->toTimeString(),
                        'nominal'            => $p['nominal'],
                        'kode_unik'          => $p['kode_unik'],
                        'total'              => $p['total'],
                        'via_bayar'          => $p['via_bayar'],
                        'status_bayar'       => $p['status_bayar'],
                        'channel_donasi'     => $p['channel_donasi'],
                        'channel_konversi'   => $p['channel_konversi'],
                        'sumber_data'        => $source,
                        'ref_asal'           => $p['ref_asal'],
                        'batch_tarik'        => $batchTarik,
                        'tgl_input'          => now(),
                    ]);
                }

                // 3. Catat Kontrol Tarik
                KontrolTarik::updateOrCreate(
                    ['sumber' => $source, 'periode' => $periode],
                    [
                        'tgl_tarik'    => now(),
                        'jml_baris'    => count($parsedRows),
                        'id_pertama'   => $firstId,
                        'id_terakhir'  => $lastId,
                        'ditarik_oleh' => $userName,
                        'catatan'      => "Impor {$source} batch {$batchTarik} sukses.",
                    ]
                );

                DB::commit();

                return [
                    'status'            => 'SUCCESS',
                    'message'           => "Berhasil mengimpor " . count($parsedRows) . " transaksi {$source} periode {$periode}.",
                    'batch_tarik'       => $batchTarik,
                    'total_rows'        => count($parsedRows),
                    'tanpa_nomor_count' => $tanpaNomorCount,
                    'new_donors_count'  => count($unregisteredNumbers),
                ];
            } catch (\Throwable $e) {
                DB::rollBack();
                Log::error("Gagal commit impor donasi {$source}: " . $e->getMessage());
                return [
                    'status'  => 'ERROR',
                    'message' => 'Gagal menyimpan transaksi ke database: ' . $e->getMessage(),
                ];
            }
        }

        // Return status gerbang berhenti untuk tampilan Papan Kerja
        return [
            'status'                => $isReady ? 'SIAP' : 'STOP',
            'sumber'                => $source,
            'periode'               => $periode,
            'total_rows'            => count($parsedRows),
            'first_id'              => $firstId,
            'last_id'               => $lastId,
            'duplicate_invoices'    => $duplicateInvoices,
            'date_errors'           => $dateErrors,
            'unmapped_campaigns'    => $unmappedCampaigns,
            'unregistered_numbers'  => $unregisteredNumbers,
            'tanpa_nomor_count'     => $tanpaNomorCount,
            'parsed_rows'           => $parsedRows,
        ];
    }

    /**
     * =========================================================================
     * ALUR B: MASUK DATA BLAST EVERPRO & DIAGNOSA INTEGRITAS
     * =========================================================================
     */
    public function processBlastImport(
        string $filePath,
        string $batchLabel, // perkenalan-YYYY-MM-DD, jumat-YYYY-MM-DD, payday-YYYY-MM-DD
        string $userName,
        ?string $extension = null
    ): array {
        $fileData = $this->readFile($filePath, $extension);
        $headers = $fileData['headers'];
        $rows = $fileData['rows'];

        $headerMap = [];
        foreach ($headers as $idx => $name) {
            $key = strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', str_replace(' ', '_', $name)));
            $headerMap[$key] = $idx;
        }

        $importedCount = 0;
        $duplicateCount = 0;
        $invalidNumbersCount = 0;
        $statusCounts = ['READ' => 0, 'DELIVERED' => 0, 'SUCCESS' => 0, 'FAILED' => 0, 'LAINNYA' => 0];
        $uniqueNumbers = [];

        DB::beginTransaction();
        try {
            foreach ($rows as $r) {
                $rawNumber = $r[$headerMap['receiver_number'] ?? $headerMap['receiver'] ?? $headerMap['no_hp'] ?? 0] ?? '';
                $broadcastId = $r[$headerMap['broadcast_id'] ?? $headerMap['broadcast'] ?? $headerMap['id_broadcast'] ?? 1] ?? '';
                $messageId = $r[$headerMap['message_id'] ?? $headerMap['id_pesan'] ?? 2] ?? null;
                $rawStatus = $r[$headerMap['status'] ?? $headerMap['delivery_status'] ?? 3] ?? '';
                $rawTimestamp = $r[$headerMap['timestamp'] ?? $headerMap['time'] ?? $headerMap['waktu'] ?? 4] ?? null;

                $normPhone = MasterNomor::normalize($rawNumber);
                $cleanNumber = $normPhone['number'] ?: preg_replace('/[^0-9]/', '', (string)$rawNumber);

                if (!str_starts_with($cleanNumber, '62')) {
                    $invalidNumbersCount++;
                }

                if (empty($cleanNumber) || empty($broadcastId)) {
                    continue;
                }

                // Tolak duplikat kunci unik: receiver_number + broadcast_id
                $exists = RawBlast::where('receiver_number', $cleanNumber)
                    ->where('broadcast_id', $broadcastId)
                    ->exists();

                if ($exists) {
                    $duplicateCount++;
                    continue;
                }

                $statusNorm = RawBlast::normalizeStatus($rawStatus);
                $statusCounts[$statusNorm] = ($statusCounts[$statusNorm] ?? 0) + 1;
                $uniqueNumbers[$cleanNumber] = true;

                $dt = $this->parseDateString($rawTimestamp) ?: now();

                RawBlast::create([
                    'receiver_number' => $cleanNumber,
                    'broadcast_id'    => (string)$broadcastId,
                    'message_id'      => $messageId,
                    'status'          => $statusNorm,
                    'timestamp_blast' => $dt,
                    'batch_tarik'     => $batchLabel,
                    'raw_json'        => $r,
                ]);

                // Jika jenis broadcast adalah 'perkenalan' dan bukan FAILED -> catat ke ref_perkenalan_terkirim
                if (str_starts_with($batchLabel, 'perkenalan') && $statusNorm !== 'FAILED') {
                    RefPerkenalanTerkirim::updateOrCreate(
                        ['no_hp' => $cleanNumber],
                        [
                            'tgl_dikirim'  => $dt->toDateString(),
                            'broadcast_id' => (string)$broadcastId,
                            'catatan'      => "Dikenalkan lewat {$batchLabel}",
                        ]
                    );
                }

                $importedCount++;
            }

            // Catat ke Kontrol Blast
            KontrolBlast::create([
                'tgl_blast'        => now()->toDateString(),
                'tgl_tarik'        => now(),
                'batch_label'      => $batchLabel,
                'jml_baris_export' => count($rows),
                'jml_nomor_unik'   => count($uniqueNumbers),
                'ditarik_oleh'     => $userName,
                'catatan'          => "Impor {$importedCount} pesan. Duplikat ditolak: {$duplicateCount}.",
            ]);

            DB::commit();

            return [
                'status'           => 'SUCCESS',
                'message'          => "Berhasil mengimpor {$importedCount} pesan blast. ({$duplicateCount} duplikat ditolak).",
                'batch_label'      => $batchLabel,
                'total_rows'       => count($rows),
                'imported_count'   => $importedCount,
                'duplicate_count'  => $duplicateCount,
                'unique_numbers'   => count($uniqueNumbers),
                'status_breakdown' => $statusCounts,
                'diagnosa'         => $this->getDiagnosaBlast(),
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Gagal impor blast: " . $e->getMessage());
            return [
                'status'  => 'ERROR',
                'message' => 'Gagal memproses ekspor Everpro: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * 6 Pemeriksa Wajib Diagnosa Integritas Riwayat Blast (Sheet DIAGNOSA).
     */
    public function getDiagnosaBlast(): array
    {
        $totalRaw = RawBlast::count();
        $totalExportLogs = (int) KontrolBlast::sum('jml_baris_export');
        $uniqueNumbers = RawBlast::distinct('receiver_number')->count('receiver_number');

        $read = RawBlast::where('status', 'READ')->count();
        $delivered = RawBlast::where('status', 'DELIVERED')->count();
        $success = RawBlast::where('status', 'SUCCESS')->count();
        $failed = RawBlast::where('status', 'FAILED')->count();
        $lainnya = RawBlast::where('status', 'LAINNYA')->count();

        $non62Count = RawBlast::where('receiver_number', 'not like', '62%')->count();
        $emptyStatusCount = RawBlast::whereNull('status')->orWhere('status', '')->count();

        return [
            'check_1_total_match' => [
                'name'    => 'Total baris riwayat = Total baris di log ekspor',
                'passed'  => $totalRaw === $totalExportLogs || $totalExportLogs === 0,
                'value'   => "{$totalRaw} vs {$totalExportLogs}",
            ],
            'check_2_unique_numbers' => [
                'name'    => 'Nomor unik terdaftar di riwayat',
                'passed'  => $uniqueNumbers > 0 || $totalRaw === 0,
                'value'   => "{$uniqueNumbers} nomor unik",
            ],
            'check_3_status_sum' => [
                'name'    => 'READ + DELIVERED + SUCCESS + FAILED = Total pesan',
                'passed'  => ($read + $delivered + $success + $failed + $lainnya) === $totalRaw,
                'value'   => "R:{$read}, D:{$delivered}, S:{$success}, F:{$failed}",
            ],
            'check_4_no_lainnya' => [
                'name'    => 'Status LAINNYA wajib = 0 (Everpro tidak menambah status baru)',
                'passed'  => $lainnya === 0,
                'value'   => "{$lainnya} status lainnya",
            ],
            'check_5_prefix_62' => [
                'name'    => 'Nomor tidak diawali 62 wajib = 0',
                'passed'  => $non62Count === 0,
                'value'   => "{$non62Count} nomor non-62",
            ],
            'check_6_empty_status' => [
                'name'    => 'Status kosong dari platform (hanya dicatat)',
                'passed'  => true,
                'value'   => "{$emptyStatusCount} baris",
            ],
        ];
    }

    /**
     * =========================================================================
     * ALUR E: CEK NOMOR WHATSAPP (WBS PRO) & NOMOR MATI
     * =========================================================================
     */
    public function processWbsDeadNumbersImport(
        string $filePath,
        ?string $batchLabel,
        string $userName,
        ?string $extension = null
    ): array {
        $fileData = $this->readFile($filePath, $extension);
        $headers = $fileData['headers'];
        $rows = $fileData['rows'];

        $headerMap = [];
        foreach ($headers as $idx => $name) {
            $key = strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', str_replace(' ', '_', $name)));
            $headerMap[$key] = $idx;
        }

        $deadNumbersMarked = 0;
        $batchLabel = $batchLabel ?: ('wbs-' . date('YmdHis'));

        DB::beginTransaction();
        try {
            foreach ($rows as $r) {
                $rawNumber = $r[$headerMap['phone'] ?? $headerMap['no_hp'] ?? $headerMap['nomor'] ?? $headerMap['whatsapp'] ?? 0] ?? '';
                $rawStatus = strtolower(trim((string)($r[$headerMap['status'] ?? $headerMap['wa_status'] ?? $headerMap['status_wa'] ?? 1] ?? '')));

                $normPhone = MasterNomor::normalize($rawNumber);
                $cleanNumber = $normPhone['number'] ?: preg_replace('/[^0-9]/', '', (string)$rawNumber);

                if (empty($cleanNumber)) {
                    continue;
                }

                // Deteksi apakah nomor mati: mengandung 'mati', 'invalid', 'tidak aktif', 'bukan wa', 'not found', '0'
                $isDead = (
                    str_contains($rawStatus, 'mati') ||
                    str_contains($rawStatus, 'inact') ||
                    str_contains($rawStatus, 'invalid') ||
                    str_contains($rawStatus, 'bukan') ||
                    str_contains($rawStatus, 'not') ||
                    str_contains($rawStatus, 'fail') ||
                    $rawStatus === '0' ||
                    $rawStatus === 'false'
                );

                if ($isDead) {
                    // 1. Simpan ke daftar nomor mati (raw_cek_wa)
                    RawCekWa::create([
                        'no_hp'       => $cleanNumber,
                        'is_wa_aktif' => false,
                        'tgl_cek'     => now()->toDateString(),
                        'catatan'     => "Hasil WBS Pro via {$batchLabel} (diunggah oleh {$userName})",
                        'batch_tarik' => $batchLabel,
                    ]);

                    // 2. Tandai wa_aktif = 0 di master_nomor (status_nomor tetap aktif sesuai aturan)
                    MasterNomor::where('no_hp', $cleanNumber)->update([
                        'wa_aktif' => false,
                    ]);

                    $deadNumbersMarked++;
                }
            }

            DB::commit();

            return [
                'status'              => 'SUCCESS',
                'message'             => "Berhasil memproses hasil WBS Pro: {$deadNumbersMarked} nomor mati berhasil dicatat dan dinonaktifkan dari daftar kirim.",
                'dead_numbers_marked' => $deadNumbersMarked,
                'batch_label'         => $batchLabel,
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Gagal proses WBS Pro: " . $e->getMessage());
            return [
                'status'  => 'ERROR',
                'message' => 'Gagal memproses hasil WBS Pro: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * =========================================================================
     * ALUR C: REKAP PER NOMOR & WATERFALL KEPUTUSAN (PUSAT SISTEM)
     * =========================================================================
     */

    /**
     * Menghitung metrik blast, donasi, dan keputusan waterfall untuk satu nomor HP.
     */
    public function computeWaterfallForNumber(string $rawPhone): array
    {
        $norm = MasterNomor::normalize($rawPhone);
        $phone = $norm['number'] ?: preg_replace('/[^0-9]/', '', (string)$rawPhone);

        // Ambil ambang batas dari pengaturan dinamis
        $thFailedBeruntun = (int) HarpaSetting::get('threshold_failed_beruntun', 2);
        $thRead0 = (int) HarpaSetting::get('threshold_total_blast_read0', 3);
        $thBlastSejakRead = (int) HarpaSetting::get('threshold_blast_sejak_read', 3);
        $maxBulanDonasi = (int) HarpaSetting::get('donasi_terakhir_max_bulan', 0);

        // 1. Cek Validitas Format Nomor (Langkah 1 Waterfall)
        if (!$norm['is_valid']) {
            return [
                'no_hp'            => $rawPhone,
                'donatur_id'       => 'TANPA-NOMOR',
                'nama'             => 'Nomor Tidak Valid',
                'segmen'           => 'ARSIP Nomor Tidak Valid',
                'jadwal'           => 'BERHENTI',
                'alasan'           => 'Format nomor tidak diawali 628 atau panjang digit di luar 11–14 digit.',
                'total_blast'      => 0,
                'read'             => 0,
                'delivered'        => 0,
                'success'          => 0,
                'failed'           => 0,
                'failed_beruntun'  => 0,
                'blast_sejak_read' => 0,
                'read_terakhir'    => null,
                'pernah_donasi'    => false,
                'total_donasi'     => 0,
                'wa_aktif'         => false,
            ];
        }

        // Cek data nomor di master_nomor
        $masterNomor = MasterNomor::with('donatur')->find($phone);
        $donatur = $masterNomor?->donatur;
        $donaturId = $donatur?->donatur_id;
        $nama = $donatur?->nama_lengkap ?: 'Nomor ' . $phone;
        $waAktif = $masterNomor ? (bool)$masterNomor->wa_aktif : !RawCekWa::where('no_hp', $phone)->exists();

        // 2. Metrik Riwayat Blast
        $blasts = RawBlast::where('receiver_number', $phone)
            ->orderBy('timestamp_blast', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $totalBlast = $blasts->count();
        $readCount = $blasts->where('status', 'READ')->count();
        $deliveredCount = $blasts->where('status', 'DELIVERED')->count();
        $successCount = $blasts->where('status', 'SUCCESS')->count();
        $failedCount = $blasts->where('status', 'FAILED')->count();

        $blastPertama = $blasts->last()?->timestamp_blast;
        $blastTerakhir = $blasts->first()?->timestamp_blast;

        $lastReadRecord = $blasts->firstWhere('status', 'READ');
        $readTerakhir = $lastReadRecord?->timestamp_blast;

        if ($readTerakhir) {
            $blastSejakRead = $blasts->filter(fn($b) => $b->timestamp_blast > $readTerakhir)->count();
        } else {
            $blastSejakRead = $totalBlast;
        }

        // Hitung failed_beruntun di ujung riwayat
        $failedBeruntun = 0;
        foreach ($blasts as $b) {
            if ($b->status === 'FAILED') {
                $failedBeruntun++;
            } else {
                break; // Stop di non-FAILED pertama dari ujung riwayat
            }
        }

        // 3. Metrik Donasi
        $donasiQuery = TransaksiDonasi::where('status_bayar', 'success')
            ->where(function($q) use ($phone, $donaturId) {
                if ($donaturId) {
                    $q->where('donatur_id', $donaturId)->orWhere('no_hp_input', $phone);
                } else {
                    $q->where('no_hp_input', $phone);
                }
            });

        if ($maxBulanDonasi > 0) {
            $donasiQuery->where('tanggal', '>=', now()->subMonths($maxBulanDonasi)->toDateString());
        }

        $donasiList = $donasiQuery->orderBy('tanggal', 'desc')->get();
        $totalDonasi = (float) $donasiList->sum('nominal');
        $donasiCount = $donasiList->count();
        $pernahDonasi = ($donasiCount > 0);
        $tglDonasiTerakhir = $donasiList->first()?->tanggal;

        // 4. WATERFALL KEPUTUSAN (Aturan pertama yang cocok yang dipakai)
        $segmen = 'PANTAU Data Belum Cukup';
        $jadwal = 'BLAST JUMAT (PANTAU)';
        $alasan = "Riwayat blast ({$totalBlast}x) belum mencukupi untuk keputusan segmen tetap.";

        if ($pernahDonasi) {
            // Langkah 2: Pernah Donasi -> AKTIF Donatur
            $segmen = 'AKTIF Donatur';
            if ($failedBeruntun >= $thFailedBeruntun) {
                $jadwal = 'BLAST JUMAT – CEK NOMOR';
                $alasan = "Pernah berdonasi total Rp " . number_format($totalDonasi, 0, ',', '.') . " ({$donasiCount}x), namun gagal terkirim {$failedBeruntun}x berturut-turut di ujung riwayat. Perlu verifikasi nomor.";
            } else {
                $jadwal = 'BLAST JUMAT';
                $alasan = "Pernah berdonasi total Rp " . number_format($totalDonasi, 0, ',', '.') . " ({$donasiCount}x transaksi).";
            }
        } elseif ($failedBeruntun >= $thFailedBeruntun) {
            // Langkah 3: failed_beruntun >= 2 -> ARSIP Nomor Bermasalah
            $segmen = 'ARSIP Nomor Bermasalah';
            $jadwal = 'BERHENTI';
            $alasan = "Gagal terkirim {$failedBeruntun}x berturut-turut di ujung riwayat blast.";
        } elseif ($totalBlast >= $thRead0 && $readCount === 0) {
            // Langkah 4: total_blast >= 3 dan read == 0 -> ARSIP Tidak Pernah Baca
            $segmen = 'ARSIP Tidak Pernah Baca';
            $jadwal = 'BERHENTI';
            $alasan = "Sudah {$totalBlast}x dikirimi blast, belum pernah dibuka sama sekali.";
        } elseif ($blastSejakRead >= $thBlastSejakRead) {
            // Langkah 5: blast_sejak_read >= 3 -> ARSIP Berhenti Baca
            $segmen = 'ARSIP Berhenti Baca';
            $jadwal = 'BERHENTI';
            $lastReadStr = $readTerakhir ? $readTerakhir->format('d M Y') : 'sebelumnya';
            $alasan = "{$blastSejakRead}x blast tidak dibuka sejak terakhir dibaca pada {$lastReadStr}.";
        } elseif ($readCount > 0) {
            // Langkah 6: read > 0 -> AKTIF Pembaca
            $segmen = 'AKTIF Pembaca';
            $jadwal = 'BLAST PAYDAY (TGL 1-5)';
            $alasan = "Pernah membaca pesan blast ({$readCount}x dibaca).";
        }

        if (!$waAktif) {
            $alasan .= " [PERINGATAN: Nomor tercatat mati di WhatsApp dari hasil WBS Pro]";
        }

        return [
            'no_hp'               => $phone,
            'donatur_id'          => $donaturId ?: 'PROSPEK',
            'nama'                => $nama,
            'segmen'              => $segmen,
            'jadwal'              => $jadwal,
            'alasan'              => $alasan,
            'total_blast'         => $totalBlast,
            'read'                => $readCount,
            'delivered'           => $deliveredCount,
            'success'             => $successCount,
            'failed'              => $failedCount,
            'blast_pertama'       => $blastPertama,
            'blast_terakhir'      => $blastTerakhir,
            'read_terakhir'       => $readTerakhir,
            'blast_sejak_read'    => $blastSejakRead,
            'failed_beruntun'     => $failedBeruntun,
            'pernah_donasi'       => $pernahDonasi,
            'total_donasi'        => $totalDonasi,
            'donasi_count'        => $donasiCount,
            'tgl_donasi_terakhir' => $tglDonasiTerakhir,
            'wa_aktif'            => $waAktif,
        ];
    }

    /**
     * Menghitung segmen untuk seluruh nomor unik di sistem.
     */
    public function getRekapSegments(?string $filterSegmen = null, ?string $search = null): array
    {
        // Ambil semua nomor unik dari master_nomor dan raw_blast
        $nomorBlast = RawBlast::distinct('receiver_number')->pluck('receiver_number');
        $nomorMaster = MasterNomor::pluck('no_hp');
        $allPhones = $nomorBlast->merge($nomorMaster)->unique()->values();

        if ($search) {
            $searchClean = preg_replace('/[^0-9]/', '', $search);
            $allPhones = $allPhones->filter(fn($p) => str_contains($p, $searchClean ?: $search));
        }

        $results = [];
        foreach ($allPhones as $phone) {
            $calc = $this->computeWaterfallForNumber($phone);
            if (!$filterSegmen || $calc['segmen'] === $filterSegmen) {
                $results[] = $calc;
            }
        }

        return $results;
    }

    /**
     * =========================================================================
     * ALUR D: DAFTAR KIRIM UNTUK TIM CRM
     * =========================================================================
     */

    /**
     * 1. KIRIM_JUMAT: segmen AKTIF Donatur, nomor wa_aktif = 1.
     */
    public function getKirimJumatList(): array
    {
        $all = $this->getRekapSegments('AKTIF Donatur');
        return array_values(array_filter($all, fn($item) => $item['wa_aktif'] === true));
    }

    /**
     * 2. KIRIM_PAYDAY: segmen AKTIF Pembaca dan PANTAU, nomor wa_aktif = 1.
     */
    public function getKirimPaydayList(): array
    {
        $pembaca = $this->getRekapSegments('AKTIF Pembaca');
        $pantau = $this->getRekapSegments('PANTAU Data Belum Cukup');
        $merged = array_merge($pembaca, $pantau);
        return array_values(array_filter($merged, fn($item) => $item['wa_aktif'] === true));
    }

    /**
     * 3. KIRIM_PERKENALAN: Donatur web baru yang belum pernah di-blast.
     */
    public function getKirimPerkenalanList(?string $bulan = null, ?string $sumber = null): array
    {
        // Nomor yang sudah ada di raw_blast atau sudah pernah dikenalkan
        $everBlasted = RawBlast::distinct('receiver_number')->pluck('receiver_number')->all();
        $everIntroduced = RefPerkenalanTerkirim::pluck('no_hp')->all();
        $deadNumbers = RawCekWa::pluck('no_hp')->all();
        $excludedPhones = array_flip(array_merge($everBlasted, $everIntroduced, $deadNumbers));

        // Transaksi donasi sukses dari web (FH atau IM), bukan WA
        $query = TransaksiDonasi::where('status_bayar', 'success')
            ->whereIn('channel_donasi', ['web_fh', 'web_im'])
            ->with(['donatur', 'campaign']);

        if ($bulan) {
            $query->where('tanggal', 'like', "{$bulan}%");
        }
        if ($sumber && in_array(strtoupper($sumber), ['FH', 'IM'])) {
            $query->where('sumber_data', strtoupper($sumber));
        }

        $donations = $query->orderBy('total', 'desc')->get();

        // Agregasi per nomor HP
        $grouped = [];
        foreach ($donations as $tx) {
            $norm = MasterNomor::normalize($tx->no_hp_input);
            $phone = $norm['number'];

            if (!$norm['is_valid'] || isset($excludedPhones[$phone])) {
                continue;
            }

            if (!isset($grouped[$phone])) {
                $grouped[$phone] = [
                    'no_hp'               => $phone,
                    'donatur_id'          => $tx->donatur_id,
                    'nama_lengkap'        => $tx->donatur?->nama_lengkap ?: 'Donatur Web',
                    'total_donasi'        => 0,
                    'frekuensi'           => 0,
                    'tgl_donasi_terakhir' => $tx->tanggal->toDateString(),
                    'bulan_donasi'        => $tx->tanggal->format('Y-m'),
                    'sumber'              => $tx->sumber_data,
                    'program_terakhir'    => $tx->campaign?->title ?: ($tx->campaign_title_raw ?: '-'),
                    'catatan_crm'         => '',
                ];
            }

            $grouped[$phone]['total_donasi'] += (float)$tx->total;
            $grouped[$phone]['frekuensi']++;
            if ($tx->tanggal->toDateString() > $grouped[$phone]['tgl_donasi_terakhir']) {
                $grouped[$phone]['tgl_donasi_terakhir'] = $tx->tanggal->toDateString();
            }
        }

        // Urutkan dari total donasi terbesar
        uasort($grouped, fn($a, $b) => $b['total_donasi'] <=> $a['total_donasi']);

        return array_values($grouped);
    }

    /**
     * 4. KIRIM_JUMAT_BARU: Nomor yang dikenalkan dalam 28 hari terakhir, belum ada di KIRIM_JUMAT.
     */
    public function getKirimJumatBaruList(): array
    {
        $graceDays = (int) HarpaSetting::get('perkenalan_grace_days', 28);
        $thresholdDate = now()->subDays($graceDays)->toDateString();

        $introduced = RefPerkenalanTerkirim::where('tgl_dikirim', '>=', $thresholdDate)->get();
        $jumatListPhones = array_column($this->getKirimJumatList(), 'no_hp');
        $jumatPhoneMap = array_flip($jumatListPhones);

        $results = [];
        foreach ($introduced as $intro) {
            $phone = $intro->no_hp;

            // Jangan masukkan jika sudah ada di KIRIM_JUMAT
            if (isset($jumatPhoneMap[$phone])) {
                continue;
            }

            $master = MasterNomor::with('donatur')->find($phone);
            if ($master && !$master->wa_aktif) {
                continue; // Jangan masukkan nomor mati
            }

            $daysAgo = Carbon::parse($intro->tgl_dikirim)->diffInDays(now());
            $results[] = [
                'no_hp'         => $phone,
                'donatur_id'    => $master?->donatur_id ?: 'DNR-BARU',
                'nama_lengkap'  => $master?->donatur?->nama_lengkap ?: 'Donatur Baru',
                'tgl_dikenalkan'=> $intro->tgl_dikirim,
                'sisa_hari_baru'=> max(0, $graceDays - $daysAgo),
                'catatan'       => "Dikenalkan {$daysAgo} hari lalu ({$intro->catatan})",
                'catatan_crm'   => '',
            ];
        }

        return $results;
    }

    /**
     * 5. ALERT NOMOR DONATUR MATI: Donatur yang nomornya tercatat mati untuk ditindaklanjuti CRM meminta nomor baru.
     */
    public function getAlertNomorMatiList(): array
    {
        $deadMaster = MasterNomor::where('wa_aktif', false)
            ->with(['donatur' => function($q) {
                $q->with('transaksiSukses');
            }])
            ->get();

        $results = [];
        foreach ($deadMaster as $item) {
            $donatur = $item->donatur;
            if (!$donatur) continue;

            $totalDonasi = $donatur->transaksiSukses->sum('nominal');
            $donasiTerakhir = $donatur->transaksiSukses->sortByDesc('tanggal')->first()?->tanggal;

            $results[] = [
                'no_hp_lama'       => $item->no_hp,
                'donatur_id'       => $donatur->donatur_id,
                'nama_lengkap'     => $donatur->nama_lengkap,
                'total_donasi'     => $totalDonasi,
                'donasi_terakhir'  => $donasiTerakhir ? $donasiTerakhir->toDateString() : '-',
                'pic_crm'          => $donatur->pic_prospek ?: 'Staff CRM',
                'catatan'          => 'Nomor terdeteksi mati via WBS Pro. Hubungi donatur via email / media alternatif untuk pembaharuan nomor.',
            ];
        }

        return $results;
    }

    /**
     * =========================================================================
     * ALUR F: ANALITIK DONATUR & RFM HARPA
     * =========================================================================
     */

    /**
     * Laporan Hitung Donatur: Evaluasi setiap donatur pada 3 sumbu terpisah.
     */
    public function getLaporanHitungDonatur(): array
    {
        $donaturs = MasterDonatur::with(['transaksiSukses', 'nomor'])->get();
        $now = Carbon::now();

        $thChampFreq = (int) HarpaSetting::get('threshold_champion_min_freq', 3);
        $thChampAmount = (float) HarpaSetting::get('threshold_champion_min_amount', 1000000);
        $thChampDays = (int) HarpaSetting::get('threshold_days_champion_vs_at_risk', 90);
        $thFreshDays = (int) HarpaSetting::get('threshold_days_fresh', 30);
        $thPotentialDays = (int) HarpaSetting::get('threshold_days_potential_vs_dormant', 180);
        $windowMonths = (int) HarpaSetting::get('window_rfm_months', 12);

        $results = [];
        foreach ($donaturs as $d) {
            $transaksi = $d->transaksiSukses;
            $frekuensi = $transaksi->count();
            $totalDonasi = (float)$transaksi->sum('nominal');
            $latestTx = $transaksi->sortByDesc('tanggal')->first();
            $firstTx = $transaksi->sortBy('tanggal')->first();

            $daysSinceLast = $latestTx ? $now->diffInDays(Carbon::parse($latestTx->tanggal)) : 9999;
            $daysSinceFirst = $firstTx ? $now->diffInDays(Carbon::parse($firstTx->tanggal)) : 9999;

            // 1. Sumbu Kategori (frekuensi donasi)
            $kategori = ($frekuensi >= 3) ? 'LOYAL' : (($frekuensi >= 1) ? 'AKTIF' : 'BARU');

            // 2. Sumbu Badge (total donasi nominal)
            if ($totalDonasi >= 10000000) {
                $badge = 'PLATINUM';
            } elseif ($totalDonasi >= 1000000) {
                $badge = 'GOLD';
            } elseif ($totalDonasi > 0) {
                $badge = 'SILVER';
            } else {
                $badge = 'NONE';
            }

            // 3. Sumbu Segmen RFM (jendela 12 bulan)
            if ($frekuensi == 0) {
                $segmenRfm = 'PROSPEK';
            } elseif ($frekuensi >= $thChampFreq && $totalDonasi >= $thChampAmount) {
                $segmenRfm = ($daysSinceLast <= $thChampDays) ? 'CHAMPION' : 'HIGH VALUE AT RISK';
            } elseif ($frekuensi >= 3 || ($frekuensi >= 2 && $daysSinceLast <= 120)) {
                $segmenRfm = 'LOYAL';
            } elseif ($daysSinceFirst <= $thFreshDays) {
                $segmenRfm = 'FRESH';
            } elseif ($daysSinceLast <= $thPotentialDays) {
                $segmenRfm = 'POTENTIAL';
            } else {
                $segmenRfm = 'DORMANT';
            }

            $results[] = [
                'donatur_id'       => $d->donatur_id,
                'nama_lengkap'     => $d->nama_lengkap,
                'no_hp'            => $d->no_hp,
                'kategori'         => $kategori,
                'badge'            => $badge,
                'segmen_rfm'       => $segmenRfm,
                'frekuensi'        => $frekuensi,
                'total_donasi'     => $totalDonasi,
                'donasi_terakhir'  => $latestTx ? $latestTx->tanggal->toDateString() : '-',
                'days_since_last'  => $daysSinceLast < 9999 ? $daysSinceLast : '-',
            ];
        }

        return $results;
    }

    /**
     * Laporan Kohort Retensi Donatur per Bulan Donasi Pertama.
     */
    public function getLaporanKohort(): array
    {
        $donaturs = MasterDonatur::with('transaksiSukses')->get();
        $cohortGroups = [];

        foreach ($donaturs as $d) {
            $transaksi = $d->transaksiSukses->sortBy('tanggal');
            if ($transaksi->isEmpty()) continue;

            $firstMonth = Carbon::parse($transaksi->first()->tanggal)->format('Y-m');
            if (!isset($cohortGroups[$firstMonth])) {
                $cohortGroups[$firstMonth] = [
                    'cohort_size' => 0,
                    'months'      => [],
                ];
            }
            $cohortGroups[$firstMonth]['cohort_size']++;

            $firstDate = Carbon::parse($transaksi->first()->tanggal);
            foreach ($transaksi as $tx) {
                $txDate = Carbon::parse($tx->tanggal);
                $diffMonths = $firstDate->diffInMonths($txDate);
                $cohortGroups[$firstMonth]['months'][$diffMonths] = ($cohortGroups[$firstMonth]['months'][$diffMonths] ?? 0) + 1;
            }
        }

        krsort($cohortGroups);
        return array_slice($cohortGroups, 0, 12, true);
    }

    /**
     * Laporan Donatur Tak Baca (Donasi dalam periode, tapi tidak pernah membaca blast Everpro).
     */
    public function getLaporanDonaturTakBaca(?string $startDate = null, ?string $endDate = null): array
    {
        $blastStartDate = HarpaSetting::get('blast_start_date', '2026-06-12');
        $startDate = $startDate ?: $blastStartDate;
        $endDate = $endDate ?: now()->toDateString();

        // Cari transaksi dalam rentang tanggal
        $txList = TransaksiDonasi::where('status_bayar', 'success')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->with(['donatur', 'donatur.nomor'])
            ->get();

        $donaturIds = $txList->pluck('donatur_id')->unique();
        $results = [];

        foreach ($donaturIds as $did) {
            $d = MasterDonatur::with(['transaksiSukses', 'nomor'])->find($did);
            if (!$d) continue;

            $phones = $d->nomor->pluck('no_hp')->all();
            if ($d->no_hp) $phones[] = $d->no_hp;
            $phones = array_unique($phones);

            // Cek apakah pernah baca blast
            $readCount = RawBlast::whereIn('receiver_number', $phones)
                ->where('status', 'READ')
                ->where('timestamp_blast', '>=', $blastStartDate)
                ->count();

            if ($readCount === 0) {
                $totalBlast = RawBlast::whereIn('receiver_number', $phones)->count();
                $results[] = [
                    'donatur_id'       => $d->donatur_id,
                    'nama_lengkap'     => $d->nama_lengkap,
                    'no_hp'            => $d->no_hp,
                    'total_donasi'     => $d->transaksiSukses->sum('nominal'),
                    'frekuensi'        => $d->transaksiSukses->count(),
                    'donasi_terakhir'  => $d->transaksiSukses->sortByDesc('tanggal')->first()?->tanggal->toDateString(),
                    'total_blast'      => $totalBlast,
                    'read_blast'       => 0,
                    'catatan'          => "Berdonasi sejak {$startDate}, dikirimi {$totalBlast}x blast, belum pernah membuka pesan.",
                ];
            }
        }

        return $results;
    }
}


