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
use App\Models\PaymentVia;
use App\Models\Program;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Shuchkin\SimpleXLSXGen;

class HarpaSpreadsheetExportService
{
    protected HarpaDataEngineService $engine;

    public function __construct(HarpaDataEngineService $engine)
    {
        $this->engine = $engine;
    }

    /**
     * Format baris header dengan warna brand Deep Navy (#063f5c) dan teks putih tebal.
     */
    protected function styledHeaders(array $columns): array
    {
        return array_map(function ($col) {
            return '<style bgcolor="#063f5c" color="#FFFFFF"><b>' . htmlspecialchars((string) $col, ENT_QUOTES, 'UTF-8') . '</b></style>';
        }, $columns);
    }

    /**
     * Paksa nilai ID dan Nomor HP menjadi teks murni di Excel (Rule 1: ID & No HP selalu teks).
     */
    protected function txt($val): string
    {
        if ($val === null || $val === '') {
            return '';
        }
        return "\0" . (string) $val;
    }

    /**
     * 1. EKSPOR WORKBOOK HARPA_DB_INDUK (.xlsx) — 20 Sheet Sesuai Spreadsheet Link 1.
     */
    public function generateDbIndukWorkbook(): string
    {
        $xlsx = SimpleXLSXGen::create('HARPA_DB_INDUK');

        // 1. PANDUAN
        $xlsx->addSheet([
            $this->styledHeaders(['Bagian', 'Aturan / Keterangan', 'Implementasi Sistem Web Harpa']),
            ['APA INI', 'Database Induk Yayasan Harpa Indonesia Madani (INDUK -> BLAST -> ANALITIK)', 'Terhubung langsung di database Website Harpa'],
            ['ATURAN 1', 'ID selalu teks (donatur_id, donasi_id, dan no_hp format 628...)', 'Sudah dikunci sebagai sel teks murni pada ekspor ini'],
            ['ATURAN 2', 'Sifat orang disimpan di master_donatur; transaksi_donasi hanya menyimpan donatur_id', 'Relasi normalized di master_donatur & transaksi_donasi'],
            ['ATURAN 3', 'Jangan menyimpan hasil hitungan rumus permanen di sheet mentah', 'Segmen, kategori, badge, dan RFM dihitung realtime'],
            ['ATURAN 4', 'Kolom tanpa data dibiarkan kosong', 'Nilai kosong tidak diisi angka palsu'],
            ['ATURAN 5', 'Sheet raw_fh, raw_im, raw_wa adalah arsip data mentah tarikan asli', 'Disimpan terpisah di tabel raw_fh_im & raw_wa'],
        ], 'PANDUAN');

        // 2. REF_DAFTAR
        $xlsx->addSheet([
            $this->styledHeaders([
                'wilayah', 'jenis_bantuan', 'jenis_dana', 'penerima', 'momen',
                'via_bayar', 'status_bayar', 'channel_donasi', 'channel_konversi',
                'channel_akuisisi', 'panggilan', 'jenis_kelamin', 'profil_donatur',
                'sumber_data', 'status_nomor', 'kantor_layanan',
            ]),
            ['Palestina', 'Air Bersih', 'Sedekah', 'Umum', 'Non-Momen', 'transfer', 'success', 'web_fh', 'iklan', 'web_fh', 'Bapak', 'L', 'Individu', 'FH', 'aktif', 'Pusat'],
            ['Suriah', 'Pangan', 'Infak', 'Yatim', 'Ramadhan', 'instant', 'waiting', 'web_im', 'blast_everpro', 'web_im', 'Ibu', 'P', 'Perusahaan', 'IM', 'lama', 'Cabang'],
            ['Nusantara', 'Medis', 'Zakat Fitrah', 'Dhuafa', 'Idul Fitri', 'va', 'failed', 'whatsapp', 'crm_personal', 'whatsapp', 'Kak', '', 'Komunitas', 'WA', 'salah', ''],
            ['Dunia Islam', 'Infrastruktur', 'Zakat Maal', 'Lansia', 'Idul Adha', 'qris', '', 'offline', 'organik', 'offline', '', '', '', '', '', ''],
        ], 'REF_DAFTAR');

        // 3. ref_bank
        $bankRows = [$this->styledHeaders(['bank_id', 'nama_bank', 'jenis'])];
        if (Schema::hasTable('payment_via')) {
            $payments = PaymentVia::orderBy('id')->get();
            foreach ($payments as $idx => $p) {
                $bankRows[] = [
                    $this->txt('BNK' . str_pad((string) ($idx + 1), 2, '0', STR_PAD_LEFT)),
                    $p->name,
                    $p->provider ?: 'bank',
                ];
            }
        }
        if (count($bankRows) === 1) {
            $bankRows[] = [$this->txt('BNK01'), 'BSI', 'bank'];
            $bankRows[] = [$this->txt('BNK02'), 'BCA', 'bank'];
            $bankRows[] = [$this->txt('BNK03'), 'BRI', 'bank'];
            $bankRows[] = [$this->txt('BNK04'), 'Mandiri', 'bank'];
        }
        $xlsx->addSheet($bankRows, 'ref_bank');

        // 4. ref_segmen
        $xlsx->addSheet([
            $this->styledHeaders(['segmen', 'tindakan']),
            ['PROSPEK', 'Blast Jumat, belum perlu japri'],
            ['CHAMPION', 'Telepon langsung, tawarkan kunjungan program'],
            ['HIGH VALUE AT RISK', 'Telepon CEO / Leader CRM minggu ini'],
            ['LOYAL', 'Kirim laporan dampak + ajak jadi donatur rutin'],
            ['FRESH', 'Kirim pesan perkenalan resmi dan sapaan personal'],
            ['POTENTIAL', 'Follow up program unggulan terdekat via WhatsApp'],
            ['DORMANT', 'Blast reaktivasi program kemanusiaan mendesak'],
        ], 'ref_segmen');

        // 5. ref_staf
        $stafRows = [$this->styledHeaders(['staf_id', 'nama_staf', 'peran', 'is_aktif'])];
        $staffs = User::whereIn('role', ['admin', 'operator', 'finance', 'program_manager', 'crm_staff', 'marketing', 'field_worker'])
            ->orderBy('id')
            ->get();
        foreach ($staffs as $idx => $st) {
            $stafRows[] = [
                $this->txt('STF' . str_pad((string) ($idx + 1), 2, '0', STR_PAD_LEFT)),
                $st->name,
                strtoupper($st->role_label ?? $st->role),
                1,
            ];
        }
        $xlsx->addSheet($stafRows, 'ref_staf');

        // 6. ref_campaign_alias
        $aliasRows = [$this->styledHeaders(['teks_asli', 'campaign_id', 'nama_campaign_standar', 'program_id', 'catatan'])];
        foreach (RefCampaignAlias::orderBy('id')->get() as $al) {
            $aliasRows[] = [
                $al->teks_asli,
                $al->campaign_id ? $this->txt('CMP' . str_pad((string) $al->campaign_id, 4, '0', STR_PAD_LEFT)) : '',
                $al->nama_campaign_standar ?: '',
                $al->program_id ? $this->txt('PRG' . str_pad((string) $al->program_id, 3, '0', STR_PAD_LEFT)) : '',
                $al->catatan ?: '',
            ];
        }
        $xlsx->addSheet($aliasRows, 'ref_campaign_alias');

        // 7. master_program
        $programRows = [$this->styledHeaders(['program_id', 'nama_program', 'wilayah', 'jenis_bantuan', 'jenis_dana', 'penerima', 'momen', 'is_aktif'])];
        if (Schema::hasTable('programs')) {
            foreach (Program::with('programGroup')->orderBy('id')->get() as $pr) {
                $programRows[] = [
                    $this->txt('PRG' . str_pad((string) $pr->id, 3, '0', STR_PAD_LEFT)),
                    $pr->name,
                    'Palestina',
                    $pr->programGroup?->name ?: 'Kemanusiaan',
                    'Sedekah',
                    'Umum',
                    'Non-Momen',
                    strtolower((string) $pr->status) === 'aktif' || $pr->status == 1 ? 1 : 0,
                ];
            }
        }
        $xlsx->addSheet($programRows, 'master_program');

        // 8. master_campaign
        $campRows = [$this->styledHeaders(['campaign_id', 'program_id', 'nama_campaign', 'sumber_data', 'tgl_mulai', 'tgl_selesai', 'target_dana', 'is_aktif'])];
        foreach (Campaign::orderBy('id')->get() as $cp) {
            $campRows[] = [
                $this->txt('CMP' . str_pad((string) $cp->id, 4, '0', STR_PAD_LEFT)),
                $cp->group_id ? $this->txt('PRG' . str_pad((string) $cp->group_id, 3, '0', STR_PAD_LEFT)) : '',
                $cp->title,
                $cp->category ?: 'web',
                $cp->created_at ? $cp->created_at->toDateString() : '',
                '',
                (float) $cp->target_amount,
                $cp->status === 'approved' ? 1 : 0,
            ];
        }
        $xlsx->addSheet($campRows, 'master_campaign');

        // 9. master_donatur
        $donaturRows = [$this->styledHeaders([
            'donatur_id', 'no_hp', 'nama_lengkap', 'nama_panggilan', 'panggilan',
            'jenis_kelamin', 'email', 'kota', 'provinsi', 'profil_donatur',
            'tgl_registrasi', 'sumber_informasi', 'channel_akuisisi', 'pic_prospek',
            'kantor_layanan', 'is_aktif', 'catatan',
        ])];
        foreach (MasterDonatur::orderBy('donatur_id')->get() as $d) {
            $donaturRows[] = [
                $this->txt($d->donatur_id),
                $this->txt($d->no_hp),
                $d->nama_lengkap,
                $d->nama_panggilan ?: '',
                $d->panggilan ?: '',
                $d->jenis_kelamin ?: '',
                $d->email ?: '',
                $d->kota ?: '',
                $d->provinsi ?: '',
                $d->profil_donatur ?: '',
                $d->tgl_registrasi ? $d->tgl_registrasi->toDateString() : '',
                $d->sumber_informasi ?: '',
                $d->channel_akuisisi ?: '',
                $d->pic_prospek ?: '',
                $d->kantor_layanan ?: '',
                $d->is_aktif ? 1 : 0,
                $d->catatan ?: '',
            ];
        }
        $xlsx->addSheet($donaturRows, 'master_donatur');

        // 10. master_nomor
        $nomorRows = [$this->styledHeaders(['no_hp', 'donatur_id', 'status_nomor', 'wa_aktif', 'tgl_cek_wa', 'tgl_catat', 'catatan'])];
        foreach (MasterNomor::orderBy('no_hp')->get() as $n) {
            $nomorRows[] = [
                $this->txt($n->no_hp),
                $this->txt($n->donatur_id),
                $n->status_nomor ?: 'aktif',
                $n->wa_aktif ? 1 : 0,
                $n->updated_at ? $n->updated_at->toDateString() : '',
                $n->tgl_catat ? $n->tgl_catat->toDateString() : '',
                $n->catatan ?: '',
            ];
        }
        $xlsx->addSheet($nomorRows, 'master_nomor');

        // 11. transaksi_donasi
        $txHeaders = [
            'donasi_id', 'donatur_id', 'no_hp_input', 'campaign_id', 'campaign_title_raw',
            'tanggal', 'jam', 'nominal', 'kode_unik', 'total', 'via_bayar', 'bank_id',
            'status_bayar', 'channel_donasi', 'channel_konversi', 'pic_transaksi',
            'utm_source', 'utm_campaign', 'sumber_data', 'ref_asal', 'batch_tarik',
        ];
        $txRows = [$this->styledHeaders($txHeaders)];
        $olahFh = [$this->styledHeaders($txHeaders)];
        $olahIm = [$this->styledHeaders($txHeaders)];
        $olahWa = [$this->styledHeaders($txHeaders)];

        foreach (TransaksiDonasi::orderBy('tanggal', 'desc')->get() as $tx) {
            $rowArr = [
                $this->txt($tx->donasi_id),
                $this->txt($tx->donatur_id),
                $this->txt($tx->no_hp_input),
                $tx->campaign_id ? $this->txt('CMP' . str_pad((string) $tx->campaign_id, 4, '0', STR_PAD_LEFT)) : '',
                $tx->campaign_title_raw ?: '',
                $tx->tanggal ? $tx->tanggal->toDateString() : '',
                $tx->jam ?: '',
                (float) $tx->nominal,
                (int) $tx->kode_unik,
                (float) $tx->total,
                $tx->via_bayar ?: '',
                $this->txt($tx->bank_id),
                $tx->status_bayar ?: '',
                $tx->channel_donasi ?: '',
                $tx->channel_konversi ?: '',
                $tx->pic_transaksi ?: '',
                $this->txt($tx->utm_source),
                $this->txt($tx->utm_campaign),
                $tx->sumber_data ?: '',
                $this->txt($tx->ref_asal),
                $tx->batch_tarik ?: '',
            ];
            $txRows[] = $rowArr;

            $src = strtoupper((string) $tx->sumber_data);
            if ($src === 'FH') {
                $olahFh[] = $rowArr;
            } elseif ($src === 'IM') {
                $olahIm[] = $rowArr;
            } elseif ($src === 'WA') {
                $olahWa[] = $rowArr;
            }
        }
        $xlsx->addSheet($txRows, 'transaksi_donasi');

        // 12. KONTROL_TARIK
        $ktRows = [$this->styledHeaders(['sumber', 'periode', 'tgl_tarik', 'jml_baris', 'id_pertama', 'id_terakhir', 'ditarik_oleh', 'catatan'])];
        foreach (KontrolTarik::orderBy('tgl_tarik', 'desc')->get() as $kt) {
            $ktRows[] = [
                $kt->sumber,
                $this->txt($kt->periode),
                $kt->tgl_tarik ? Carbon::parse($kt->tgl_tarik)->format('Y-m-d H:i:s') : '',
                (int) $kt->jml_baris,
                $this->txt($kt->id_pertama),
                $this->txt($kt->id_terakhir),
                $kt->ditarik_oleh ?: '',
                $kt->catatan ?: '',
            ];
        }
        $xlsx->addSheet($ktRows, 'KONTROL_TARIK');

        // 13. PAPAN_KERJA
        $papanRows = [$this->styledHeaders(['jenis_kendala', 'referensi_id_atau_teks', 'no_hp_input', 'tanggal', 'nominal', 'keterangan'])];
        $unmappedTitles = TransaksiDonasi::whereNull('campaign_id')->whereNotNull('campaign_title_raw')->distinct()->pluck('campaign_title_raw');
        foreach ($unmappedTitles as $titleRaw) {
            $papanRows[] = ['Campaign Belum Dipetakan', $titleRaw, '', '', '', 'Petakan judul ini ke Master Campaign / Alias'];
        }
        foreach (TransaksiDonasi::where('donatur_id', 'TANPA-NOMOR')->latest('tanggal')->get() as $tn) {
            $papanRows[] = [
                'Transaksi Tanpa Nomor',
                $this->txt($tn->donasi_id),
                $this->txt($tn->no_hp_input),
                $tn->tanggal ? $tn->tanggal->toDateString() : '',
                (float) $tn->total,
                'Lengkapi nomor HP / daftarkan donatur_id',
            ];
        }
        $xlsx->addSheet($papanRows, 'PAPAN_KERJA');

        // 14 & 16: raw_fh & raw_im + 15 & 17: olah_fh & olah_im
        $rawFh = $this->buildRawFhImRows('FH');
        $rawIm = $this->buildRawFhImRows('IM');
        $xlsx->addSheet($rawFh, 'raw_fh');
        $xlsx->addSheet($olahFh, 'olah_fh');
        $xlsx->addSheet($rawIm, 'raw_im');
        $xlsx->addSheet($olahIm, 'olah_im');

        // 18 & 19: raw_wa & olah_wa
        $rawWa = $this->buildRawWaRows();
        $xlsx->addSheet($rawWa, 'raw_wa');
        $xlsx->addSheet($olahWa, 'olah_wa');

        // 20. raw_cek_wa
        $cekWaRows = [$this->styledHeaders(['no_hp', 'is_wa_aktif', 'tgl_cek', 'catatan', 'batch_tarik'])];
        foreach (RawCekWa::orderBy('tgl_cek', 'desc')->get() as $cw) {
            $cekWaRows[] = [
                $this->txt($cw->no_hp),
                $cw->is_wa_aktif ? 1 : 0,
                $cw->tgl_cek ? Carbon::parse($cw->tgl_cek)->toDateString() : '',
                $cw->catatan ?: '',
                $cw->batch_tarik ?: '',
            ];
        }
        $xlsx->addSheet($cekWaRows, 'raw_cek_wa');

        return (string) $xlsx;
    }

    /**
     * Helper ubah baris raw_fh_im menjadi tabel datar berdasarkan key JSON.
     */
    protected function buildRawFhImRows(string $source): array
    {
        $items = RawFhIm::where('sumber', $source)->orderBy('id')->get();
        if ($items->isEmpty()) {
            return [$this->styledHeaders(['sumber', 'invoice_id', 'batch_tarik', 'tgl_tempel', 'data_json'])];
        }

        $jsonKeys = [];
        foreach ($items as $item) {
            if (is_array($item->data_json)) {
                foreach (array_keys($item->data_json) as $k) {
                    $jsonKeys[$k] = true;
                }
            }
        }
        $cols = array_merge(['invoice_id', 'batch_tarik', 'tgl_tempel'], array_keys($jsonKeys));
        $rows = [$this->styledHeaders($cols)];

        foreach ($items as $item) {
            $data = is_array($item->data_json) ? $item->data_json : [];
            $row = [
                $this->txt($item->invoice_id),
                $item->batch_tarik ?: '',
                $item->tgl_tempel ? $item->tgl_tempel->format('Y-m-d H:i:s') : '',
            ];
            foreach (array_keys($jsonKeys) as $k) {
                $val = $data[$k] ?? '';
                if (is_array($val)) {
                    $val = json_encode($val);
                }
                // Jaga nomor HP atau UTM panjang sebagai teks murni
                if (is_string($val) && preg_match('/^\d{9,}$/', trim($val))) {
                    $val = $this->txt(trim($val));
                }
                $row[] = $val;
            }
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * Helper ubah baris raw_wa menjadi tabel datar.
     */
    protected function buildRawWaRows(): array
    {
        $items = RawWa::orderBy('id')->get();
        if ($items->isEmpty()) {
            return [$this->styledHeaders(['transaksi_id', 'batch_tarik', 'tgl_tempel', 'data_json'])];
        }

        $jsonKeys = [];
        foreach ($items as $item) {
            if (is_array($item->data_json)) {
                foreach (array_keys($item->data_json) as $k) {
                    $jsonKeys[$k] = true;
                }
            }
        }
        $cols = array_merge(['transaksi_id', 'batch_tarik', 'tgl_tempel'], array_keys($jsonKeys));
        $rows = [$this->styledHeaders($cols)];

        foreach ($items as $item) {
            $data = is_array($item->data_json) ? $item->data_json : [];
            $row = [
                $this->txt($item->transaksi_id),
                $item->batch_tarik ?: '',
                $item->tgl_tempel ? $item->tgl_tempel->format('Y-m-d H:i:s') : '',
            ];
            foreach (array_keys($jsonKeys) as $k) {
                $val = $data[$k] ?? '';
                if (is_array($val)) {
                    $val = json_encode($val);
                }
                if (is_string($val) && preg_match('/^\d{9,}$/', trim($val))) {
                    $val = $this->txt(trim($val));
                }
                $row[] = $val;
            }
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * 2. EKSPOR WORKBOOK HARPA_DB_BLAST (.xlsx) — 19 Sheet Sesuai Spreadsheet Link 2.
     */
    public function generateDbBlastWorkbook(): string
    {
        $xlsx = SimpleXLSXGen::create('DB_BLAST');

        // 1. PANDUAN
        $xlsx->addSheet([
            $this->styledHeaders(['Tahap', 'Keterangan', 'Menu di Website Harpa']),
            ['Alur B', 'Upload log pengiriman Everpro ke raw_blast & jalankan 6 cek diagnosa', 'Riwayat Blast & Diagnosa'],
            ['Alur C', 'Klasifikasi setiap nomor dengan aturan waterfall (AKTIF Donatur, Pembaca, PANTAU, ARSIP)', 'Rekap & Waterfall'],
            ['Alur D', 'Daftar nomor siap kirim per kelompok (Jumat, Payday, Perkenalan, Jumat Baru, Alert Mati)', 'Daftar Kirim'],
            ['Alur E', 'Tandai nomor WA mati dari hasil validasi WBS Pro agar tidak buang kuota', 'Nomor Mati (WBS Pro)'],
        ], 'PANDUAN');

        // 2. PENGATURAN
        $xlsx->addSheet($this->buildSettingsRows(), 'PENGATURAN');

        // 3. raw_blast
        $blastRows = [$this->styledHeaders(['receiver_number', 'broadcast_id', 'message_id', 'status', 'timestamp_blast', 'batch_tarik'])];
        foreach (RawBlast::orderBy('timestamp_blast', 'desc')->get() as $rb) {
            $blastRows[] = [
                $this->txt($rb->receiver_number),
                $this->txt($rb->broadcast_id),
                $this->txt($rb->message_id),
                $rb->status,
                $rb->timestamp_blast ? $rb->timestamp_blast->format('Y-m-d H:i:s') : '',
                $rb->batch_tarik ?: '',
            ];
        }
        $xlsx->addSheet($blastRows, 'raw_blast');

        // 4. KONTROL
        $kbRows = [$this->styledHeaders(['tgl_blast', 'tgl_tarik', 'batch_label', 'jml_baris_export', 'jml_nomor_unik', 'ditarik_oleh', 'catatan'])];
        foreach (KontrolBlast::orderBy('tgl_blast', 'desc')->get() as $kb) {
            $kbRows[] = [
                $kb->tgl_blast ? Carbon::parse($kb->tgl_blast)->toDateString() : '',
                $kb->tgl_tarik ? Carbon::parse($kb->tgl_tarik)->format('Y-m-d H:i:s') : '',
                $kb->batch_label,
                (int) $kb->jml_baris_export,
                (int) $kb->jml_nomor_unik,
                $kb->ditarik_oleh ?: '',
                $kb->catatan ?: '',
            ];
        }
        $xlsx->addSheet($kbRows, 'KONTROL');

        // 5. transaksi_blast
        $tbRows = [$this->styledHeaders(['donasi_id', 'donatur_id', 'no_hp_input', 'tanggal', 'nominal', 'channel_konversi', 'utm_campaign'])];
        foreach (TransaksiDonasi::where('status_bayar', 'success')->where('channel_konversi', 'like', '%blast%')->orderBy('tanggal', 'desc')->get() as $tb) {
            $tbRows[] = [
                $this->txt($tb->donasi_id),
                $this->txt($tb->donatur_id),
                $this->txt($tb->no_hp_input),
                $tb->tanggal ? $tb->tanggal->toDateString() : '',
                (float) $tb->total,
                $tb->channel_konversi ?: '',
                $this->txt($tb->utm_campaign),
            ];
        }
        $xlsx->addSheet($tbRows, 'transaksi_blast');

        // 6. rekap_nomor
        $rekapRows = [$this->styledHeaders([
            'no_hp', 'donatur_id', 'nama', 'segmen', 'jadwal', 'alasan',
            'total_blast', 'read', 'delivered', 'success', 'failed',
            'blast_pertama', 'blast_terakhir', 'read_terakhir',
            'blast_sejak_read', 'failed_beruntun', 'pernah_donasi',
            'total_donasi', 'donasi_count', 'tgl_donasi_terakhir', 'wa_aktif',
        ])];
        $allRekap = $this->engine->getRekapSegments();
        foreach ($allRekap as $r) {
            $rekapRows[] = [
                $this->txt($r['no_hp']),
                $this->txt($r['donatur_id']),
                $r['nama'],
                $r['segmen'],
                $r['jadwal'],
                $r['alasan'],
                (int) $r['total_blast'],
                (int) $r['read'],
                (int) $r['delivered'],
                (int) $r['success'],
                (int) $r['failed'],
                $r['blast_pertama'] ? Carbon::parse($r['blast_pertama'])->toDateString() : '',
                $r['blast_terakhir'] ? Carbon::parse($r['blast_terakhir'])->toDateString() : '',
                $r['read_terakhir'] ? Carbon::parse($r['read_terakhir'])->toDateString() : '',
                (int) $r['blast_sejak_read'],
                (int) $r['failed_beruntun'],
                $r['pernah_donasi'] ? 1 : 0,
                (float) $r['total_donasi'],
                (int) $r['donasi_count'],
                $r['tgl_donasi_terakhir'] ? Carbon::parse($r['tgl_donasi_terakhir'])->toDateString() : '',
                $r['wa_aktif'] ? 1 : 0,
            ];
        }
        $xlsx->addSheet($rekapRows, 'rekap_nomor');

        // 7 & 8. cek_wa & src_cek_wa
        $cekWa = [$this->styledHeaders(['no_hp', 'is_wa_aktif', 'tgl_cek', 'catatan', 'batch_tarik'])];
        foreach (RawCekWa::orderBy('tgl_cek', 'desc')->get() as $cw) {
            $cekWa[] = [
                $this->txt($cw->no_hp),
                $cw->is_wa_aktif ? 1 : 0,
                $cw->tgl_cek ? Carbon::parse($cw->tgl_cek)->toDateString() : '',
                $cw->catatan ?: '',
                $cw->batch_tarik ?: '',
            ];
        }
        $xlsx->addSheet($cekWa, 'cek_wa');
        $xlsx->addSheet($cekWa, 'src_cek_wa');

        // 9. KIRIM_JUMAT
        $kjRows = [$this->styledHeaders(['Nomor HP', 'Donatur ID', 'Nama Lengkap', 'Segmen', 'Jadwal / Status', 'Total Donasi', 'Read Terakhir', 'Alasan Waterfall'])];
        foreach ($this->engine->getKirimJumatList() as $item) {
            $kjRows[] = [
                $this->txt($item['no_hp']),
                $this->txt($item['donatur_id']),
                $item['nama'],
                $item['segmen'],
                $item['jadwal'],
                (float) $item['total_donasi'],
                $item['read_terakhir'] ? Carbon::parse($item['read_terakhir'])->toDateString() : '-',
                $item['alasan'],
            ];
        }
        $xlsx->addSheet($kjRows, 'KIRIM_JUMAT');

        // 10. KIRIM_PERKENALAN
        $kpRows = [$this->styledHeaders(['Nomor HP', 'Total Donasi', 'Frekuensi', 'Tgl Donasi Terakhir', 'Donatur ID', 'Nama Lengkap', 'Bulan Donasi', 'Sumber', 'Program Terakhir', 'Catatan CRM'])];
        foreach ($this->engine->getKirimPerkenalanList() as $item) {
            $kpRows[] = [
                $this->txt($item['no_hp']),
                (float) $item['total_donasi'],
                (int) $item['frekuensi'],
                $item['tgl_donasi_terakhir'],
                $this->txt($item['donatur_id']),
                $item['nama_lengkap'],
                $this->txt($item['bulan_donasi']),
                $item['sumber'],
                $item['program_terakhir'],
                $item['catatan_crm'],
            ];
        }
        $xlsx->addSheet($kpRows, 'KIRIM_PERKENALAN');

        // 11. KIRIM_PAYDAY
        $kpayRows = [$this->styledHeaders(['Nomor HP', 'Donatur ID', 'Nama Lengkap', 'Segmen', 'Jadwal / Status', 'Total Donasi', 'Read Terakhir', 'Alasan Waterfall'])];
        foreach ($this->engine->getKirimPaydayList() as $item) {
            $kpayRows[] = [
                $this->txt($item['no_hp']),
                $this->txt($item['donatur_id']),
                $item['nama'],
                $item['segmen'],
                $item['jadwal'],
                (float) $item['total_donasi'],
                $item['read_terakhir'] ? Carbon::parse($item['read_terakhir'])->toDateString() : '-',
                $item['alasan'],
            ];
        }
        $xlsx->addSheet($kpayRows, 'KIRIM_PAYDAY');

        // 12. ALERT_NOMOR_DONATUR_MATI
        $alertRows = [$this->styledHeaders(['Nomor HP Lama', 'Donatur ID', 'Nama Lengkap', 'Total Donasi', 'Donasi Terakhir', 'PIC CRM', 'Catatan CRM'])];
        foreach ($this->engine->getAlertNomorMatiList() as $item) {
            $alertRows[] = [
                $this->txt($item['no_hp_lama']),
                $this->txt($item['donatur_id']),
                $item['nama_lengkap'],
                (float) $item['total_donasi'],
                $item['donasi_terakhir'],
                $item['pic_crm'],
                $item['catatan'],
            ];
        }
        $xlsx->addSheet($alertRows, 'ALERT_NOMOR_DONATUR_MATI');

        // 13. ref_perkenalan_terkirim
        $introRows = [$this->styledHeaders(['no_hp', 'tgl_dikirim', 'broadcast_id', 'catatan'])];
        foreach (RefPerkenalanTerkirim::orderBy('tgl_dikirim', 'desc')->get() as $rp) {
            $introRows[] = [
                $this->txt($rp->no_hp),
                $rp->tgl_dikirim ? Carbon::parse($rp->tgl_dikirim)->toDateString() : '',
                $this->txt($rp->broadcast_id),
                $rp->catatan ?: '',
            ];
        }
        $xlsx->addSheet($introRows, 'ref_perkenalan_terkirim');

        // 14. src_campaign
        $srcCamp = [$this->styledHeaders(['campaign_id', 'nama_campaign', 'status', 'target_amount'])];
        foreach (Campaign::orderBy('id')->get() as $cp) {
            $srcCamp[] = [
                $this->txt('CMP' . str_pad((string) $cp->id, 4, '0', STR_PAD_LEFT)),
                $cp->title,
                $cp->status,
                (float) $cp->target_amount,
            ];
        }
        $xlsx->addSheet($srcCamp, 'src_campaign');

        // 15. DIAGNOSA
        $diagRows = [$this->styledHeaders(['Kode Pemeriksaan', 'Nama Pemeriksaan Integritas', 'Status Lulus', 'Hasil Evaluasi'])];
        foreach ($this->engine->getDiagnosaBlast() as $k => $chk) {
            $diagRows[] = [$k, $chk['name'], $chk['passed'] ? 'PASS' : 'FAIL', $chk['value']];
        }
        $xlsx->addSheet($diagRows, 'DIAGNOSA');

        // 16. RUMUS (Dokumentasi logika yang sudah diotomasi server)
        $xlsx->addSheet([
            $this->styledHeaders(['Sheet', 'Logika / Rumus Asli', 'Status di Sistem Web Harpa']),
            ['rekap_nomor', 'Waterfall: Pernah Donasi -> Failed >= 2 -> Blast >= 3 Read=0 -> Blast Sejak Read >= 3 -> Read > 0 -> Pantau', 'Terotomatisasi di HarpaDataEngineService::computeWaterfallForNumber()'],
            ['KIRIM_JUMAT', 'FILTER rekap_nomor segmen = AKTIF Donatur AND wa_aktif = 1', 'Terotomatisasi di HarpaDataEngineService::getKirimJumatList()'],
            ['KIRIM_PAYDAY', 'FILTER rekap_nomor segmen IN (AKTIF Pembaca, PANTAU) AND wa_aktif = 1', 'Terotomatisasi di HarpaDataEngineService::getKirimPaydayList()'],
            ['KIRIM_PERKENALAN', 'Donatur web_fh/web_im yang belum pernah masuk raw_blast & ref_perkenalan_terkirim', 'Terotomatisasi di HarpaDataEngineService::getKirimPerkenalanList()'],
        ], 'RUMUS');

        // 17, 18, 19: src_donasi, src_donatur, src_nomor
        $xlsx->addSheet($this->buildSrcDonasiRows(), 'src_donasi');
        $xlsx->addSheet($this->buildSrcDonaturRows(), 'src_donatur');
        $xlsx->addSheet($this->buildSrcNomorRows(), 'src_nomor');

        return (string) $xlsx;
    }

    /**
     * 3. EKSPOR WORKBOOK HARPA_ANALITIK (.xlsx) — 14 Sheet Sesuai Spreadsheet Link 3.
     */
    public function generateAnalitikWorkbook(?int $year = null, ?int $month = null, ?string $startDate = null, ?string $endDate = null): string
    {
        $year = $year ?: (int) date('Y');
        $month = $month ?: (int) date('m');
        $xlsx = SimpleXLSXGen::create('HARPA_ANALITIK');

        // 1. PANDUAN
        $xlsx->addSheet([
            $this->styledHeaders(['Bagian', 'Aturan Analisis Donatur Harpa']),
            ['APA INI', 'Ujung rantai data: HARPA_DB_INDUK -> HARPA_DB_BLAST -> HARPA_ANALITIK'],
            ['3 SUMBU', 'Kategori (Frekuensi), Badge (Total Rupiah), dan 7 Segmen RFM dinilai mandiri'],
            ['7 SEGMEN RFM', 'PROSPEK, CHAMPION, HIGH VALUE AT RISK, LOYAL, FRESH, POTENTIAL, DORMANT'],
        ], 'PANDUAN');

        // 2. PENGATURAN
        $xlsx->addSheet($this->buildSettingsRows(), 'PENGATURAN');

        // 3. hitung_donatur
        $hitungRows = [$this->styledHeaders([
            'donatur_id', 'nama_lengkap', 'no_hp', 'kategori', 'badge',
            'segmen_rfm', 'frekuensi', 'total_donasi', 'donasi_terakhir', 'recency_hari',
        ])];
        foreach ($this->engine->getLaporanHitungDonatur() as $hd) {
            $hitungRows[] = [
                $this->txt($hd['donatur_id']),
                $hd['nama_lengkap'],
                $this->txt($hd['no_hp']),
                $hd['kategori'],
                $hd['badge'],
                $hd['segmen_rfm'],
                (int) $hd['frekuensi'],
                (float) $hd['total_donasi'],
                $hd['donasi_terakhir'],
                $hd['days_since_last'] === '-' ? '' : (int) $hd['days_since_last'],
            ];
        }
        $xlsx->addSheet($hitungRows, 'hitung_donatur');

        // 4. tracking_bulanan
        $tbData = $this->engine->getLaporanTrackingBulanan($year);
        $tbRows = [$this->styledHeaders([
            'donatur_id', 'nama_lengkap', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
            'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des', 'total', 'frekuensi', 'rata_rata', 'kategori', 'badge',
        ])];
        foreach ($tbData['items'] as $row) {
            $m = $row['monthly'];
            $tbRows[] = [
                $this->txt($row['donatur_id']),
                $row['nama_lengkap'],
                (float) $m[1], (float) $m[2], (float) $m[3], (float) $m[4], (float) $m[5], (float) $m[6],
                (float) $m[7], (float) $m[8], (float) $m[9], (float) $m[10], (float) $m[11], (float) $m[12],
                (float) $row['total'],
                (int) $row['frekuensi'],
                round((float) $row['rata_rata']),
                $row['kategori'],
                $row['badge'],
            ];
        }
        $xlsx->addSheet($tbRows, 'tracking_bulanan');

        // 5. tracking_jumat
        $tjData = $this->engine->getLaporanTrackingJumat($year, $month);
        $tjRows = [$this->styledHeaders([
            'donatur_id', 'nama_lengkap', 'bulan', 'J1', 'J2', 'J3', 'J4', 'J5',
            'total_bulan', 'jml_blast', 'jml_read', 'status_terakhir', 'catatan_crm',
        ])];
        foreach ($tjData['items'] as $row) {
            $tjRows[] = [
                $this->txt($row['donatur_id']),
                $row['nama_lengkap'],
                $month,
                (float) $row['j1'],
                (float) $row['j2'],
                (float) $row['j3'],
                (float) $row['j4'],
                (float) $row['j5'],
                (float) $row['total_bulan'],
                (int) $row['jml_blast'],
                (int) $row['jml_read'],
                $row['status_terakhir'],
                $row['catatan_crm'],
            ];
        }
        $xlsx->addSheet($tjRows, 'tracking_jumat');

        // 6. donatur_tak_baca
        $dtbRows = [$this->styledHeaders([
            'donatur_id', 'nama_lengkap', 'no_hp', 'total_donasi', 'frekuensi',
            'tgl_donasi_terakhir', 'jml_blast', 'read_blast', 'catatan_analisis',
        ])];
        foreach ($this->engine->getLaporanDonaturTakBaca($startDate, $endDate) as $dt) {
            $dtbRows[] = [
                $this->txt($dt['donatur_id']),
                $dt['nama_lengkap'],
                $this->txt($dt['no_hp']),
                (float) $dt['total_donasi'],
                (int) $dt['frekuensi'],
                $dt['donasi_terakhir'] ?: '-',
                (int) $dt['total_blast'],
                (int) $dt['read_blast'],
                $dt['catatan'],
            ];
        }
        $xlsx->addSheet($dtbRows, 'donatur_tak_baca');

        // 7. MINTA_NOMOR_BARU
        $mnbData = $this->engine->getLaporanMintaNomorBaru();
        $mnbRows = [$this->styledHeaders([
            'no_hp_lama', 'donatur_id', 'total_donasi', 'nama_lengkap',
            'nomor_baru', 'pic_crm', 'status_wa', 'donasi_terakhir', 'catatan',
        ])];
        foreach ($mnbData['items'] as $row) {
            $mnbRows[] = [
                $this->txt($row['no_hp_lama']),
                $this->txt($row['donatur_id']),
                (float) $row['total_donasi'],
                $row['nama_lengkap'],
                $this->txt($row['nomor_baru']),
                $row['pic_crm'],
                $row['status_wa'],
                $row['donasi_terakhir'],
                $row['catatan'],
            ];
        }
        $xlsx->addSheet($mnbRows, 'MINTA_NOMOR_BARU');

        // 8. kohort
        $kohortHeaders = ['bulan_gabung', 'jml_donatur'];
        for ($m = 0; $m <= 11; $m++) {
            $kohortHeaders[] = "M+{$m} (%)";
        }
        $kohortRows = [$this->styledHeaders($kohortHeaders)];
        foreach ($this->engine->getLaporanKohort() as $bulan => $info) {
            $row = [$this->txt($bulan), (int) $info['cohort_size']];
            for ($m = 0; $m <= 11; $m++) {
                $activeCount = $info['months'][$m] ?? 0;
                $pct = $info['cohort_size'] > 0 ? round(($activeCount / $info['cohort_size']) * 100, 1) : 0;
                $row[] = $activeCount > 0 ? "{$pct}%" : '-';
            }
            $kohortRows[] = $row;
        }
        $xlsx->addSheet($kohortRows, 'kohort');

        // 9. RUMUS
        $xlsx->addSheet([
            $this->styledHeaders(['Sheet', 'Rumus / Logika Perhitungan', 'Sumber Implementasi']),
            ['hitung_donatur', 'Sumbu 1 Kategori, Sumbu 2 Badge, Sumbu 3 Segmen RFM', 'HarpaDataEngineService::getLaporanHitungDonatur()'],
            ['kohort', 'Retensi donatur M+0 sampai M+11 dari bulan transaksi pertama', 'HarpaDataEngineService::getLaporanKohort()'],
            ['donatur_tak_baca', 'Donatur berdonasi pada periode dengan 0 status READ pada raw_blast', 'HarpaDataEngineService::getLaporanDonaturTakBaca()'],
            ['tracking_bulanan', 'Agregasi nominal transaksi sukses Jan-Des per donatur', 'HarpaDataEngineService::getLaporanTrackingBulanan()'],
            ['tracking_jumat', 'Pemetaan transaksi per pekan Jumat (J1-J5) vs status blast', 'HarpaDataEngineService::getLaporanTrackingJumat()'],
        ], 'RUMUS');

        // 10-14. src_cek_wa, src_donasi, src_donatur, src_segmen, src_blast
        $cekWa = [$this->styledHeaders(['no_hp', 'is_wa_aktif', 'tgl_cek', 'catatan', 'batch_tarik'])];
        foreach (RawCekWa::orderBy('tgl_cek', 'desc')->get() as $cw) {
            $cekWa[] = [
                $this->txt($cw->no_hp),
                $cw->is_wa_aktif ? 1 : 0,
                $cw->tgl_cek ? Carbon::parse($cw->tgl_cek)->toDateString() : '',
                $cw->catatan ?: '',
                $cw->batch_tarik ?: '',
            ];
        }
        $xlsx->addSheet($cekWa, 'src_cek_wa');
        $xlsx->addSheet($this->buildSrcDonasiRows(), 'src_donasi');
        $xlsx->addSheet($this->buildSrcDonaturRows(), 'src_donatur');
        $xlsx->addSheet([
            $this->styledHeaders(['segmen', 'tindakan']),
            ['PROSPEK', 'Blast Jumat, belum perlu japri'],
            ['CHAMPION', 'Telepon langsung, tawarkan kunjungan program'],
            ['HIGH VALUE AT RISK', 'Telepon CEO / Leader CRM minggu ini'],
            ['LOYAL', 'Kirim laporan dampak + ajak jadi donatur rutin'],
        ], 'src_segmen');

        $srcBlast = [$this->styledHeaders(['receiver_number', 'broadcast_id', 'status', 'timestamp_blast', 'batch_tarik'])];
        foreach (RawBlast::orderBy('timestamp_blast', 'desc')->get() as $rb) {
            $srcBlast[] = [
                $this->txt($rb->receiver_number),
                $this->txt($rb->broadcast_id),
                $rb->status,
                $rb->timestamp_blast ? $rb->timestamp_blast->format('Y-m-d H:i:s') : '',
                $rb->batch_tarik ?: '',
            ];
        }
        $xlsx->addSheet($srcBlast, 'src_blast');

        return (string) $xlsx;
    }

    protected function buildSettingsRows(): array
    {
        $rows = [$this->styledHeaders(['key', 'value', 'description'])];
        foreach (HarpaSetting::orderBy('key')->get() as $st) {
            $rows[] = [$st->key, $st->value, $st->description ?: ''];
        }
        return $rows;
    }

    protected function buildSrcDonasiRows(): array
    {
        $rows = [$this->styledHeaders(['donasi_id', 'donatur_id', 'no_hp_input', 'tanggal', 'nominal', 'total', 'via_bayar', 'status_bayar', 'sumber_data'])];
        foreach (TransaksiDonasi::orderBy('tanggal', 'desc')->get() as $tx) {
            $rows[] = [
                $this->txt($tx->donasi_id),
                $this->txt($tx->donatur_id),
                $this->txt($tx->no_hp_input),
                $tx->tanggal ? $tx->tanggal->toDateString() : '',
                (float) $tx->nominal,
                (float) $tx->total,
                $tx->via_bayar ?: '',
                $tx->status_bayar ?: '',
                $tx->sumber_data ?: '',
            ];
        }
        return $rows;
    }

    protected function buildSrcDonaturRows(): array
    {
        $rows = [$this->styledHeaders(['donatur_id', 'no_hp', 'nama_lengkap', 'email', 'kota', 'tgl_registrasi', 'sumber_informasi', 'pic_prospek', 'is_aktif'])];
        foreach (MasterDonatur::orderBy('donatur_id')->get() as $d) {
            $rows[] = [
                $this->txt($d->donatur_id),
                $this->txt($d->no_hp),
                $d->nama_lengkap,
                $d->email ?: '',
                $d->kota ?: '',
                $d->tgl_registrasi ? $d->tgl_registrasi->toDateString() : '',
                $d->sumber_informasi ?: '',
                $d->pic_prospek ?: '',
                $d->is_aktif ? 1 : 0,
            ];
        }
        return $rows;
    }

    protected function buildSrcNomorRows(): array
    {
        $rows = [$this->styledHeaders(['no_hp', 'donatur_id', 'status_nomor', 'wa_aktif', 'tgl_catat', 'catatan'])];
        foreach (MasterNomor::orderBy('no_hp')->get() as $n) {
            $rows[] = [
                $this->txt($n->no_hp),
                $this->txt($n->donatur_id),
                $n->status_nomor ?: 'aktif',
                $n->wa_aktif ? 1 : 0,
                $n->tgl_catat ? $n->tgl_catat->toDateString() : '',
                $n->catatan ?: '',
            ];
        }
        return $rows;
    }
}
