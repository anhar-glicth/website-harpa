<?php

namespace App\Http\Controllers\Harpa;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Harpa\KontrolBlast;
use App\Models\Harpa\KontrolTarik;
use App\Models\Harpa\MasterDonatur;
use App\Models\Harpa\MasterNomor;
use App\Models\Harpa\RawBlast;
use App\Models\Harpa\RawCekWa;
use App\Models\Harpa\RefCampaignAlias;
use App\Models\Harpa\RefPerkenalanTerkirim;
use App\Models\Harpa\TransaksiDonasi;
use App\Services\Harpa\HarpaDataEngineService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class HarpaDataController extends Controller
{
    protected HarpaDataEngineService $engine;

    public function __construct(HarpaDataEngineService $engine)
    {
        $this->engine = $engine;
    }

    /**
     * Layar Gerbang Berhenti / Papan Kerja (Approval Queue).
     */
    public function papanKerja(Request $request)
    {
        $pendingData = session('harpa_pending_import');

        // Alias campaign yang belum terpetakan di sistem
        $unmappedAliases = TransaksiDonasi::whereNull('campaign_id')
            ->whereNotNull('campaign_title_raw')
            ->distinct('campaign_title_raw')
            ->pluck('campaign_title_raw');

        // Nomor belum terdaftar atau berstatus TANPA-NOMOR yang bisa diperbaiki
        $tanpaNomorTransactions = TransaksiDonasi::where('donatur_id', 'TANPA-NOMOR')
            ->latest('tanggal')
            ->take(100)
            ->get();

        $campaigns = Campaign::orderBy('title')->get();

        return view('admin.harpa.papan_kerja', [
            'active'                 => 'harpa_papan_kerja',
            'pendingData'            => $pendingData,
            'unmappedAliases'        => $unmappedAliases,
            'tanpaNomorTransactions' => $tanpaNomorTransactions,
            'campaigns'              => $campaigns,
        ]);
    }

    /**
     * Menyetujui pendaftaran donatur baru dan pemetaan campaign di Papan Kerja.
     */
    public function papanKerjaApprove(Request $request)
    {
        $action = $request->input('action');

        if ($action === 'register_donor') {
            $request->validate([
                'phone' => 'required|string',
                'name'  => 'required|string|max:255',
            ]);

            $norm = MasterNomor::normalize($request->phone);
            if (!$norm['is_valid']) {
                return back()->with('error', 'Nomor tidak valid format 628 (11-14 digit).');
            }

            $phone = $norm['number'];
            if (MasterNomor::where('no_hp', $phone)->exists()) {
                return back()->with('info', 'Nomor ini sudah terdaftar.');
            }

            $nextDnr = MasterDonatur::generateNextId();
            $donatur = MasterDonatur::create([
                'donatur_id'     => $nextDnr,
                'no_hp'          => $phone,
                'nama_lengkap'   => $request->name,
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

            // Update transaksi TANPA-NOMOR jika nomor inputnya cocok
            TransaksiDonasi::where('no_hp_input', $phone)
                ->where('donatur_id', 'TANPA-NOMOR')
                ->update(['donatur_id' => $nextDnr]);

            return back()->with('success', "Donatur baru {$request->name} ({$nextDnr}) berhasil didaftarkan.");
        }

        if ($action === 'map_alias') {
            $request->validate([
                'teks_asli'   => 'required|string',
                'campaign_id' => 'required|exists:campaigns,id',
            ]);

            $campaign = Campaign::findOrFail($request->campaign_id);

            RefCampaignAlias::updateOrCreate(
                ['teks_asli' => trim($request->teks_asli)],
                [
                    'campaign_id'            => $campaign->id,
                    'nama_campaign_standar'  => $campaign->title,
                    'program_id'             => $campaign->program_id ?? null,
                    'catatan'                => 'Dipetakan via Papan Kerja oleh ' . Auth::user()->name,
                ]
            );

            // Update transaksi yang memiliki judul ini
            TransaksiDonasi::where('campaign_title_raw', trim($request->teks_asli))
                ->whereNull('campaign_id')
                ->update(['campaign_id' => $campaign->id]);

            return back()->with('success', "Judul \"{$request->teks_asli}\" berhasil dipetakan ke campaign \"{$campaign->title}\".");
        }

        return back()->with('info', 'Tidak ada aksi yang dijalankan.');
    }

    /**
     * Halaman Upload & Impor Data Donasi (Alur A: FH, IM, WA) + Input Manual.
     */
    public function importDonationsForm(Request $request)
    {
        $kontrolTarik = KontrolTarik::latest('tgl_tarik')->take(20)->get();
        $campaigns = Campaign::orderBy('title')->get();
        $latestManual = TransaksiDonasi::with('donatur')
            ->latest('tgl_input')
            ->take(8)
            ->get();

        return view('admin.harpa.import_donasi', [
            'active'         => 'harpa_import_donasi',
            'kontrolTarik'   => $kontrolTarik,
            'campaigns'      => $campaigns,
            'latestManual'   => $latestManual,
        ]);
    }

    /**
     * Simpan Donasi Manual langsung ke Sistem Data Harpa.
     */
    public function storeManualDonation(Request $request)
    {
        $request->validate([
            'nama_lengkap'       => 'required|string|max:255',
            'no_hp'              => 'required|string|max:50',
            'email'              => 'nullable|email|max:255',
            'sumber_data'        => 'required|in:WA,IM,FH,MANUAL',
            'campaign_id'        => 'nullable|exists:campaigns,id',
            'campaign_title_raw' => 'nullable|string|max:255',
            'tanggal'            => 'required|date',
            'jam'                => 'nullable|string',
            'nominal'            => 'required|numeric|min:1000',
            'via_bayar'          => 'nullable|string|max:100',
            'status_bayar'       => 'required|in:success,waiting',
            'catatan'            => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // 1. Normalisasi Nomor HP sesuai standar Harpa (628...)
            $normPhone = MasterNomor::normalize($request->no_hp);
            $cleanPhone = $normPhone['number'];
            $isPhoneValid = $normPhone['is_valid'];

            $donaturId = null;
            if ($isPhoneValid) {
                $existingNomor = MasterNomor::find($cleanPhone);
                if ($existingNomor) {
                    $donaturId = $existingNomor->donatur_id;
                    $existingDonatur = MasterDonatur::find($donaturId);
                    if ($existingDonatur) {
                        if (empty($existingDonatur->nama_lengkap) || $existingDonatur->nama_lengkap === 'Donatur') {
                            $existingDonatur->nama_lengkap = trim($request->nama_lengkap);
                        }
                        if (empty($existingDonatur->email) && $request->email) {
                            $existingDonatur->email = trim($request->email);
                        }
                        $existingDonatur->save();
                    }
                } else {
                    $donaturId = MasterDonatur::generateNextId();
                    MasterDonatur::create([
                        'donatur_id'       => $donaturId,
                        'no_hp'            => $cleanPhone,
                        'nama_lengkap'     => trim($request->nama_lengkap),
                        'email'            => $request->email ? trim($request->email) : null,
                        'is_aktif'         => true,
                        'tgl_registrasi'   => $request->tanggal ?: now()->toDateString(),
                        'sumber_informasi' => 'Input Manual (' . $request->sumber_data . ')',
                        'pic_prospek'      => Auth::user()->name,
                        'tgl_input'        => now(),
                    ]);

                    MasterNomor::create([
                        'no_hp'        => $cleanPhone,
                        'donatur_id'   => $donaturId,
                        'status_nomor' => 'aktif',
                        'wa_aktif'     => true,
                        'tgl_catat'    => now()->toDateString(),
                        'catatan'      => 'Input manual via Harpa Data Engine',
                    ]);
                }
            } else {
                $donaturId = 'TANPA-NOMOR';
            }

            // 2. Resolve Campaign Name
            $campaignId = $request->campaign_id;
            $campaignTitle = $request->campaign_title_raw;
            if ($campaignId) {
                $campaign = Campaign::find($campaignId);
                if ($campaign) {
                    $campaignTitle = $campaign->title;
                }
            }
            if (empty($campaignTitle)) {
                $campaignTitle = 'Donasi Umum';
            }

            // 3. Generate ID Donasi
            $source = $request->sumber_data ?: 'MANUAL';
            $donasiId = TransaksiDonasi::generateNextId($source);

            // 4. Simpan ke transaksi_donasi
            TransaksiDonasi::create([
                'donasi_id'          => $donasiId,
                'donatur_id'         => $donaturId,
                'no_hp_input'        => $cleanPhone ?: $request->no_hp,
                'campaign_id'        => $campaignId,
                'campaign_title_raw' => $campaignTitle,
                'tanggal'            => $request->tanggal,
                'jam'                => $request->jam ?: now()->format('H:i:s'),
                'nominal'            => $request->nominal,
                'kode_unik'          => 0,
                'total'              => $request->nominal,
                'via_bayar'          => $request->via_bayar ?: 'Transfer Bank',
                'bank_id'            => $request->via_bayar,
                'status_bayar'       => $request->status_bayar ?: 'success',
                'channel_donasi'     => strtolower($source),
                'channel_konversi'   => 'manual',
                'pic_transaksi'      => Auth::user()->name,
                'sumber_data'        => $source,
                'ref_asal'           => 'MNL-' . strtoupper(Str::random(8)),
                'batch_tarik'        => 'manual-' . date('Y-m'),
                'tgl_input'          => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.harpa.import_donasi')
                ->with('success', "Donasi manual berhasil dicatat! ID: {$donasiId} | Donatur: {$request->nama_lengkap} ({$donaturId}) | Rp " . number_format($request->nominal, 0, ',', '.'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal simpan donasi manual Harpa: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal menyimpan donasi manual: ' . $e->getMessage());
        }
    }

    /**
     * Submit File Impor Data Donasi (FH, IM, WA).
     */
    public function importDonationsSubmit(Request $request)
    {
        $request->validate([
            'file'            => 'required|file|max:20480',
            'source'          => 'required|in:FH,IM,WA',
            'periode'         => 'required|string|regex:/^\d{4}-\d{2}$/',
            'commit_directly' => 'nullable|boolean',
        ]);

        $file = $request->file('file');
        $source = $request->source;
        $periode = $request->periode;
        $userName = Auth::user()->name;
        $commitDirectly = (bool) $request->commit_directly;

        $res = $this->engine->processDonationImport(
            $file->getRealPath(),
            $source,
            $periode,
            $userName,
            $commitDirectly,
            $file->getClientOriginalExtension()
        );

        if ($res['status'] === 'SUCCESS') {
            session()->forget('harpa_pending_import');
            return redirect()->route('admin.harpa.import_donasi')
                ->with('success', $res['message']);
        }

        if ($res['status'] === 'STOP') {
            session(['harpa_pending_import' => $res]);
            return redirect()->route('admin.harpa.papan_kerja')
                ->with('warning', "Proses impor tertahan di Gerbang Berhenti (Papan Kerja): " . ($res['message'] ?? 'Terdapat nomor baru atau judul campaign yang belum dipetakan.'));
        }

        return back()->with('error', $res['message'] ?? 'Terjadi kesalahan saat memproses data.');
    }

    /**
     * Modul Riwayat Blast Everpro (Alur B) & 6 Diagnosa Integritas.
     */
    public function blastHistory(Request $request)
    {
        $query = RawBlast::latest('timestamp_blast');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('batch')) {
            $query->where('batch_tarik', 'like', "%{$request->batch}%");
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('receiver_number', 'like', "%{$request->search}%")
                  ->orWhere('broadcast_id', 'like', "%{$request->search}%");
            });
        }

        $blasts = $query->paginate(25)->withQueryString();
        $diagnosa = $this->engine->getDiagnosaBlast();
        $kontrolBlast = KontrolBlast::latest('tgl_blast')->take(15)->get();

        return view('admin.harpa.blast_history', [
            'active'       => 'harpa_blast',
            'blasts'       => $blasts,
            'diagnosa'     => $diagnosa,
            'kontrolBlast' => $kontrolBlast,
        ]);
    }

    /**
     * Submit Impor Ekspor Blast Everpro.
     */
    public function importBlastSubmit(Request $request)
    {
        $request->validate([
            'file'        => 'required|file|max:20480',
            'batch_type'  => 'required|in:jumat,payday,perkenalan',
            'batch_date'  => 'required|date',
        ]);

        $batchLabel = $request->batch_type . '-' . $request->batch_date;
        $file = $request->file('file');
        $userName = Auth::user()->name;

        $res = $this->engine->processBlastImport(
            $file->getRealPath(),
            $batchLabel,
            $userName,
            $file->getClientOriginalExtension()
        );

        if ($res['status'] === 'SUCCESS') {
            return redirect()->route('admin.harpa.blast_history')
                ->with('success', $res['message']);
        }

        return back()->with('error', $res['message'] ?? 'Gagal memproses data blast.');
    }

    /**
     * Catat Riwayat Blast Secara Manual untuk Suatu Nomor.
     */
    public function storeManualBlast(Request $request)
    {
        $request->validate([
            'receiver_number' => 'required|string|max:50',
            'status'          => 'required|in:READ,DELIVERED,SUCCESS,FAILED,LAINNYA',
            'batch_type'      => 'required|in:jumat,payday,perkenalan',
            'batch_date'      => 'required|date',
            'catatan'         => 'nullable|string|max:255',
        ]);

        $norm = MasterNomor::normalize($request->receiver_number);
        $phone = $norm['number'];
        if (!$norm['is_valid'] || !$phone) {
            return back()->withInput()->with('error', 'Format nomor HP tidak valid (wajib 628... panjang 11-14 digit).');
        }

        $batchLabel = $request->batch_type . '-' . $request->batch_date;
        $broadcastId = 'MANUAL-' . strtoupper(Str::random(6)) . '-' . date('His');

        RawBlast::create([
            'receiver_number' => $phone,
            'broadcast_id'    => $broadcastId,
            'message_id'      => 'MSG-' . strtoupper(Str::random(8)),
            'status'          => $request->status,
            'timestamp_blast' => $request->batch_date . ' ' . date('H:i:s'),
            'batch_tarik'     => $batchLabel,
            'raw_json'        => json_encode([
                'source'  => 'manual_input',
                'user'    => Auth::user()->name,
                'catatan' => $request->catatan,
            ]),
        ]);

        return redirect()->route('admin.harpa.blast_history')
            ->with('success', "Riwayat blast manual untuk nomor {$phone} berhasil dicatat dengan status: {$request->status} ({$batchLabel})!");
    }

    /**
     * Modul Cek Nomor WhatsApp (WBS Pro) & Nomor Mati (Alur E).
     */
    public function wbsPro(Request $request)
    {
        $deadNumbers = MasterNomor::where('wa_aktif', false)
            ->with('donatur')
            ->latest('updated_at')
            ->paginate(25);

        $totalDead = MasterNomor::where('wa_aktif', false)->count();
        $recentChecks = RawCekWa::latest('tgl_cek')->take(10)->get();

        return view('admin.harpa.wbs_pro', [
            'active'       => 'harpa_wbs',
            'deadNumbers'  => $deadNumbers,
            'totalDead'    => $totalDead,
            'recentChecks' => $recentChecks,
        ]);
    }

    /**
     * Submit File Hasil Cek WBS Pro.
     */
    public function importWbsSubmit(Request $request)
    {
        $request->validate([
            'file'        => 'required|file|max:15360',
            'batch_label' => 'nullable|string|max:100',
        ]);

        $file = $request->file('file');
        $batchLabel = $request->batch_label ?: ('wbs-' . date('Y-m-d'));
        $userName = Auth::user()->name;

        $res = $this->engine->processWbsDeadNumbersImport(
            $file->getRealPath(),
            $batchLabel,
            $userName,
            $file->getClientOriginalExtension()
        );

        if ($res['status'] === 'SUCCESS') {
            return redirect()->route('admin.harpa.wbs')
                ->with('success', $res['message']);
        }

        return back()->with('error', $res['message'] ?? 'Gagal memproses hasil WBS Pro.');
    }

    /**
     * Tandai Nomor Mati / Aktif Kembali Secara Manual.
     */
    public function storeManualDeadNumber(Request $request)
    {
        $request->validate([
            'no_hp'       => 'required|string|max:50',
            'status_wa'   => 'required|in:0,1',
            'tgl_cek'     => 'required|date',
            'catatan'     => 'nullable|string|max:255',
        ]);

        $norm = MasterNomor::normalize($request->no_hp);
        $phone = $norm['number'];
        if (!$norm['is_valid'] || !$phone) {
            return back()->withInput()->with('error', 'Format nomor HP tidak valid (wajib 628... panjang 11-14 digit).');
        }

        $isWaAktif = (bool)$request->status_wa;

        DB::beginTransaction();
        try {
            // Update MasterNomor jika ada
            $master = MasterNomor::find($phone);
            if ($master) {
                $master->wa_aktif = $isWaAktif;
                if (!empty($request->catatan)) {
                    $master->catatan = $request->catatan;
                }
                $master->save();
            }

            if (!$isWaAktif) {
                // Catat di RawCekWa sebagai nomor mati
                RawCekWa::updateOrCreate(
                    ['no_hp' => $phone],
                    [
                        'is_wa_aktif' => false,
                        'tgl_cek'     => $request->tgl_cek,
                        'catatan'     => $request->catatan ?: ('Ditandai manual oleh ' . Auth::user()->name),
                        'batch_tarik' => 'manual-' . date('Y-m'),
                    ]
                );
            } else {
                // Jika aktif kembali, hapus dari catatan nomor mati
                RawCekWa::where('no_hp', $phone)->delete();
            }

            DB::commit();

            $statusText = $isWaAktif ? 'AKTIF KEMBALI' : 'MATI (TIDAK AKTIF)';
            return redirect()->route('admin.harpa.wbs')
                ->with('success', "Nomor {$phone} berhasil diperbarui statusnya menjadi: {$statusText}!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui status nomor: ' . $e->getMessage());
        }
    }

    /**
     * =========================================================================
     * ALUR C: REKAP PER NOMOR & WATERFALL KEPUTUSAN
     * =========================================================================
     */
    public function rekapSegmen(Request $request)
    {
        $filterSegmen = $request->query('segmen');
        $search = $request->query('search');

        $segments = $this->engine->getRekapSegments($filterSegmen, $search);

        // Agregasi jumlah per segmen
        $counts = [
            'total'                  => count($segments),
            'AKTIF Donatur'          => 0,
            'AKTIF Pembaca'          => 0,
            'PANTAU Data Belum Cukup'=> 0,
            'ARSIP Bermasalah'       => 0,
            'ARSIP Tidak Pernah Baca'=> 0,
            'ARSIP Berhenti Baca'    => 0,
            'ARSIP Nomor Tidak Valid'=> 0,
        ];

        // Jika ada filter, ambil total semua segmen untuk kartu ringkasan
        $allSegs = $filterSegmen ? $this->engine->getRekapSegments() : $segments;
        foreach ($allSegs as $s) {
            $segName = $s['segmen'];
            if (isset($counts[$segName])) {
                $counts[$segName]++;
            }
        }
        $counts['total'] = count($allSegs);

        return view('admin.harpa.rekap_segmen', [
            'active'       => 'harpa_rekap',
            'segments'     => $segments,
            'counts'       => $counts,
            'filterSegmen' => $filterSegmen,
            'search'       => $search,
        ]);
    }

    /**
     * =========================================================================
     * ALUR D: DAFTAR KIRIM UNTUK TIM CRM
     * =========================================================================
     */
    public function daftarKirim(Request $request)
    {
        $tab = $request->query('tab', 'jumat');
        $bulan = $request->query('bulan');
        $sumber = $request->query('sumber');

        $data = match ($tab) {
            'jumat'       => $this->engine->getKirimJumatList(),
            'payday'      => $this->engine->getKirimPaydayList(),
            'perkenalan'  => $this->engine->getKirimPerkenalanList($bulan, $sumber),
            'jumat_baru'  => $this->engine->getKirimJumatBaruList(),
            'nomor_mati'  => $this->engine->getAlertNomorMatiList(),
            default       => $this->engine->getKirimJumatList(),
        };

        return view('admin.harpa.daftar_kirim', [
            'active' => 'harpa_daftar_kirim',
            'tab'    => $tab,
            'data'   => $data,
            'bulan'  => $bulan,
            'sumber' => $sumber,
        ]);
    }

    /**
     * Ekspor Daftar Kirim ke CSV untuk Tim CRM.
     */
    public function exportDaftarKirim(Request $request, string $type)
    {
        $bulan = $request->query('bulan');
        $sumber = $request->query('sumber');
        $maskPhone = (bool)$request->query('mask', false);

        $data = match ($type) {
            'jumat'       => $this->engine->getKirimJumatList(),
            'payday'      => $this->engine->getKirimPaydayList(),
            'perkenalan'  => $this->engine->getKirimPerkenalanList($bulan, $sumber),
            'jumat_baru'  => $this->engine->getKirimJumatBaruList(),
            'nomor_mati'  => $this->engine->getAlertNomorMatiList(),
            default       => [],
        };

        $filename = "harpa_daftar_kirim_{$type}_" . date('Ymd_His') . ".csv";

        return response()->streamDownload(function() use ($type, $data, $maskPhone) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM

            if ($type === 'nomor_mati') {
                fputcsv($handle, ['Nomor HP Lama', 'Donatur ID', 'Nama Lengkap', 'Total Donasi', 'Donasi Terakhir', 'PIC CRM', 'Catatan CRM']);
                foreach ($data as $row) {
                    $phone = $maskPhone ? substr($row['no_hp_lama'], 0, 5) . '****' : $row['no_hp_lama'];
                    fputcsv($handle, [
                        $phone,
                        $row['donatur_id'],
                        $row['nama_lengkap'],
                        $row['total_donasi'],
                        $row['donasi_terakhir'],
                        $row['pic_crm'],
                        $row['catatan'],
                    ]);
                }
            } elseif ($type === 'perkenalan') {
                fputcsv($handle, ['Nomor HP', 'Total Donasi', 'Frekuensi', 'Tgl Donasi Terakhir', 'Donatur ID', 'Nama Lengkap', 'Bulan Donasi', 'Sumber', 'Program Terakhir', 'Catatan CRM']);
                foreach ($data as $row) {
                    $phone = $maskPhone ? substr($row['no_hp'], 0, 5) . '****' : $row['no_hp'];
                    fputcsv($handle, [
                        $phone,
                        $row['total_donasi'],
                        $row['frekuensi'],
                        $row['tgl_donasi_terakhir'],
                        $row['donatur_id'],
                        $row['nama_lengkap'],
                        $row['bulan_donasi'],
                        $row['sumber'],
                        $row['program_terakhir'],
                        $row['catatan_crm'],
                    ]);
                }
            } elseif ($type === 'jumat_baru') {
                fputcsv($handle, ['Nomor HP', 'Donatur ID', 'Nama Lengkap', 'Tgl Dikenalkan', 'Sisa Hari Status Baru', 'Catatan', 'Catatan CRM']);
                foreach ($data as $row) {
                    $phone = $maskPhone ? substr($row['no_hp'], 0, 5) . '****' : $row['no_hp'];
                    fputcsv($handle, [
                        $phone,
                        $row['donatur_id'],
                        $row['nama_lengkap'],
                        $row['tgl_dikenalkan'],
                        $row['sisa_hari_baru'],
                        $row['catatan'],
                        $row['catatan_crm'],
                    ]);
                }
            } else {
                fputcsv($handle, ['Nomor HP', 'Donatur ID', 'Nama Lengkap', 'Segmen', 'Jadwal / Status', 'Total Donasi', 'Read Terakhir', 'Alasan Waterfall', 'Catatan CRM']);
                foreach ($data as $row) {
                    $phone = $maskPhone ? substr($row['no_hp'], 0, 5) . '****' : $row['no_hp'];
                    fputcsv($handle, [
                        $phone,
                        $row['donatur_id'],
                        $row['nama'],
                        $row['segmen'],
                        $row['jadwal'],
                        $row['total_donasi'],
                        $row['read_terakhir'] ? $row['read_terakhir']->format('Y-m-d') : '-',
                        $row['alasan'],
                        '',
                    ]);
                }
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * =========================================================================
     * ALUR F: LAPORAN ANALITIK DONATUR
     * =========================================================================
     */
    public function laporanAnalitik(Request $request)
    {
        $tab = $request->query('tab', 'hitung_donatur');

        $hitungDonatur = ($tab === 'hitung_donatur') ? $this->engine->getLaporanHitungDonatur() : [];
        $kohort = ($tab === 'kohort') ? $this->engine->getLaporanKohort() : [];
        $donaturTakBaca = ($tab === 'donatur_tak_baca') ? $this->engine->getLaporanDonaturTakBaca(
            $request->query('start_date'),
            $request->query('end_date')
        ) : [];

        return view('admin.harpa.laporan_analitik', [
            'active'         => 'harpa_laporan',
            'tab'            => $tab,
            'hitungDonatur'  => $hitungDonatur,
            'kohort'         => $kohort,
            'donaturTakBaca' => $donaturTakBaca,
            'startDate'      => $request->query('start_date'),
            'endDate'        => $request->query('end_date'),
        ]);
    }

    /**
     * Halaman Pengaturan Ambang Batas Sistem Harpa (harpa_settings).
     */
    public function settings(Request $request)
    {
        $settings = \App\Models\Harpa\HarpaSetting::orderBy('key')->get();

        return view('admin.harpa.settings', [
            'active'   => 'harpa_settings',
            'settings' => $settings,
        ]);
    }

    /**
     * Simpan Perubahan Pengaturan Ambang Batas.
     */
    public function updateSettings(Request $request)
    {
        $inputs = $request->except(['_token']);

        foreach ($inputs as $key => $val) {
            \App\Models\Harpa\HarpaSetting::set($key, (string)$val);
        }

        return back()->with('success', 'Pengaturan ambang batas Sistem Data Harpa berhasil diperbarui.');
    }
}

