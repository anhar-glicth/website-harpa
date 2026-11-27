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
        // Sanitasi input nominal dari titik, koma, spasi, atau format Rp
        if ($request->has('nominal')) {
            $rawNominal = preg_replace('/[^0-9]/', '', (string)$request->nominal);
            $request->merge(['nominal' => $rawNominal !== '' ? (float)$rawNominal : 0]);
        }

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
     * ALUR C: Rekap per nomor & waterfall keputusan.
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
     * ALUR D: Daftar kirim untuk tim CRM.
     */
    public function daftarKirim(Request $request)
    {
        $tab = $request->query('tab', 'jumat');
        $bulan = $request->query('bulan');
        $sumber = $request->query('sumber');
        $batchDate = $request->query('batch_date', date('Y-m-d'));

        $data = match ($tab) {
            'jumat'       => $this->engine->getKirimJumatList(),
            'payday'      => $this->engine->getKirimPaydayList(),
            'perkenalan'  => $this->engine->getKirimPerkenalanList($bulan, $sumber),
            'jumat_baru'  => $this->engine->getKirimJumatBaruList(),
            'nomor_mati'  => $this->engine->getAlertNomorMatiList(),
            default       => $this->engine->getKirimJumatList(),
        };

        // Ambil riwayat blast untuk tanggal/batch ini
        $batchLabel = $tab . '-' . $batchDate;
        
        $blastRecords = RawBlast::where(function($q) use ($batchDate, $batchLabel) {
            $q->whereDate('timestamp_blast', $batchDate)
              ->orWhere('batch_tarik', $batchLabel);
        })->orderBy('timestamp_blast', 'desc')->get()->keyBy('receiver_number');

        $perkenalanMap = [];
        if ($tab === 'perkenalan') {
            $perkenalanMap = RefPerkenalanTerkirim::whereDate('tgl_dikirim', $batchDate)
                ->pluck('tgl_dikirim', 'no_hp')
                ->all();
        }

        $stats = [
            'total'     => count($data),
            'delivered' => 0,
            'read'      => 0,
            'failed'    => 0,
            'pending'   => 0,
        ];

        foreach ($data as &$row) {
            $phone = $row['no_hp'] ?? ($row['no_hp_lama'] ?? null);
            if (!$phone) {
                $row['current_status'] = 'PENDING';
                $stats['pending']++;
                continue;
            }

            $status = 'PENDING';
            if (isset($blastRecords[$phone])) {
                $rawStatus = strtoupper($blastRecords[$phone]->status);
                if (str_contains($rawStatus, 'READ')) {
                    $status = 'READ';
                } elseif (str_contains($rawStatus, 'DELIVER') || str_contains($rawStatus, 'SUCC')) {
                    $status = 'DELIVERED';
                } elseif (str_contains($rawStatus, 'FAIL')) {
                    $status = 'FAILED';
                }
            } elseif (isset($perkenalanMap[$phone])) {
                $status = 'DELIVERED';
            }

            $row['current_status'] = $status;

            if ($status === 'READ') {
                $stats['read']++;
            } elseif ($status === 'DELIVERED') {
                $stats['delivered']++;
            } elseif ($status === 'FAILED') {
                $stats['failed']++;
            } else {
                $stats['pending']++;
            }
        }
        unset($row);

        $stats['finished'] = $stats['read'] + $stats['delivered'] + $stats['failed'];
        $stats['percent'] = $stats['total'] > 0 ? round(($stats['finished'] / $stats['total']) * 100) : 0;

        $clientData = [];
        foreach ($data as $r) {
            $phone = $r['no_hp'] ?? ($r['no_hp_lama'] ?? '');
            $name = $r['nama'] ?? ($r['nama_lengkap'] ?? 'Donatur');
            $donaturId = $r['donatur_id'] ?? '-';
            $status = $r['current_status'] ?? 'PENDING';
            $totalDonasi = $r['total_donasi'] ?? 0;

            $clientData[] = [
                'no_hp'           => (string)$phone,
                'nama'            => (string)$name,
                'donatur_id'      => (string)$donaturId,
                'total_donasi'    => (int)$totalDonasi,
                'status'          => (string)$status,
                'segmen'          => (string)($r['segmen'] ?? ''),
                'jadwal'          => (string)($r['jadwal'] ?? ''),
                'alasan'          => (string)($r['alasan'] ?? ''),
                'read_terakhir'   => isset($r['read_terakhir']) && $r['read_terakhir'] ? $r['read_terakhir']->format('d M Y') : 'Belum Pernah',
                'sumber'          => (string)($r['sumber'] ?? ''),
                'bulan_donasi'    => (string)($r['bulan_donasi'] ?? ''),
                'frekuensi'       => (int)($r['frekuensi'] ?? 0),
                'program'         => (string)($r['program_terakhir'] ?? ''),
                'tgl_dikenalkan'  => (string)($r['tgl_dikenalkan'] ?? ''),
                'sisa_hari_baru'  => (int)($r['sisa_hari_baru'] ?? 0),
                'donasi_terakhir' => (string)($r['donasi_terakhir'] ?? ($r['tgl_donasi_terakhir'] ?? '-')),
                'pic_crm'         => (string)($r['pic_crm'] ?? ''),
                'catatan'         => (string)($r['catatan'] ?? ''),
            ];
        }

        return view('admin.harpa.daftar_kirim', [
            'active'     => 'harpa_daftar_kirim',
            'tab'        => $tab,
            'data'       => $data,
            'clientData' => $clientData,
            'bulan'      => $bulan,
            'sumber'     => $sumber,
            'batchDate'  => $batchDate,
            'batchLabel' => $batchLabel,
            'stats'      => $stats,
        ]);
    }

    /**
     * Update status tracking pengiriman broadcast (Alur D)
     * Mendukung update per nomor atau batch massal via AJAX
     */
    public function updateDaftarKirimStatus(Request $request)
    {
        $validated = $request->validate([
            'phones'     => 'required',
            'status'     => 'required|in:PENDING,DELIVERED,READ,FAILED',
            'tab'        => 'nullable|string',
            'batch_date' => 'nullable|date',
            'catatan'    => 'nullable|string',
        ]);

        $rawPhones = is_array($validated['phones']) ? $validated['phones'] : [$validated['phones']];
        $status = strtoupper($validated['status']);
        $tab = $validated['tab'] ?? 'jumat';
        $batchDate = $validated['batch_date'] ?? date('Y-m-d');
        $batchLabel = $tab . '-' . $batchDate;
        $catatan = $validated['catatan'] ?? null;

        $phones = [];
        foreach ($rawPhones as $p) {
            $cleaned = $this->engine->normalizePhoneNumber($p);
            if ($cleaned) {
                $phones[] = $cleaned;
            }
        }

        if (empty($phones)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada nomor WhatsApp yang valid.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            if ($status === 'PENDING') {
                // Hapus catatan blast hari/batch ini agar kembali berstatus pending
                RawBlast::whereIn('receiver_number', $phones)
                    ->where(function($q) use ($batchDate, $batchLabel) {
                        $q->whereDate('timestamp_blast', $batchDate)
                          ->orWhere('batch_tarik', $batchLabel);
                    })->delete();

                if ($tab === 'perkenalan') {
                    RefPerkenalanTerkirim::whereIn('no_hp', $phones)
                        ->whereDate('tgl_dikirim', $batchDate)
                        ->delete();
                }
            } else {
                foreach ($phones as $phone) {
                    $blast = RawBlast::where('receiver_number', $phone)
                        ->where(function($q) use ($batchDate, $batchLabel) {
                            $q->whereDate('timestamp_blast', $batchDate)
                              ->orWhere('batch_tarik', $batchLabel);
                        })->first();

                    if ($blast) {
                        $blast->status = $status;
                        $blast->save();
                    } else {
                        RawBlast::create([
                            'receiver_number' => $phone,
                            'broadcast_id'    => 'TRACKER_' . strtoupper($tab) . '_' . date('Ymd', strtotime($batchDate)),
                            'message_id'      => 'TRK_' . uniqid(),
                            'status'          => $status,
                            'timestamp_blast' => \Carbon\Carbon::parse($batchDate)->setTime(now()->hour, now()->minute, now()->second),
                            'batch_tarik'     => $batchLabel,
                            'raw_json'        => [
                                'source'     => 'daftar_kirim_tracker',
                                'tab'        => $tab,
                                'user_id'    => Auth::id(),
                                'user_name'  => Auth::user()?->name ?? 'Admin CRM',
                                'catatan'    => $catatan,
                                'updated_at' => now()->toDateTimeString(),
                            ],
                        ]);
                    }

                    if ($tab === 'perkenalan' && in_array($status, ['DELIVERED', 'READ'])) {
                        RefPerkenalanTerkirim::firstOrCreate(
                            ['no_hp' => $phone],
                            [
                                'tgl_dikirim'  => $batchDate,
                                'broadcast_id' => 'MANUAL_PERKENALAN_' . date('Ymd', strtotime($batchDate)),
                                'catatan'      => $catatan ?: 'Dikenalkan via Tracker Alur D',
                            ]
                        );
                    }

                    MasterNomor::firstOrCreate(
                        ['no_hp' => $phone],
                        [
                            'wa_aktif'     => true,
                            'diperiksa_at' => now(),
                            'sumber_cek'   => 'manual_tracker',
                        ]
                    );
                }
            }

            DB::commit();

            return response()->json([
                'success'       => true,
                'message'       => count($phones) . ' nomor berhasil diupdate ke status ' . $status,
                'status'        => $status,
                'updated_count' => count($phones),
                'phones'        => $phones,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error updateDaftarKirimStatus: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
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
     * Download template format file CSV/Excel untuk Alur Impor Harpa (Alur A, B, E)
     */
    public function downloadTemplate(string $type)
    {
        return match ($type) {
            'donasi' => $this->downloadDonasiTemplate(),
            'blast'  => $this->downloadBlastTemplate(),
            'wbs'    => $this->downloadWbsTemplate(),
            default  => abort(404, 'Template tidak ditemukan'),
        };
    }

    private function downloadDonasiTemplate()
    {
        $filename = "template_impor_donasi_harpa.csv";
        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM agar rapi di Microsoft Excel
            fputcsv($handle, [
                'invoice_id',
                'tanggal',
                'campaign',
                'nama',
                'no_hp',
                'nominal',
                'kode_unik',
                'status',
                'via_bayar',
            ]);

            $sampleRows = [
                ['INV-202611-001', '2026-11-01 08:30:15', 'Sedekah Subuh Palestina', 'Ahmad Fadhilah', '081234567890', '100000', '125', 'success', 'BCA'],
                ['INV-202611-002', '2026-11-01 10:15:40', 'Bantuan Air Bersih Gaza', 'Siti Rahmawati', '6285712345678', '250000', '340', 'success', 'Mandiri'],
                ['INV-202611-003', '2026-11-02 14:05:12', 'Paket Pangan Darurat Jabalia', 'Budi Santoso', '081987654321', '500000', '0', 'success', 'QRIS'],
                ['INV-202611-004', '2026-11-02 19:45:00', 'Hangatkan Gaza Musim Dingin', 'Hj. Nurhayati', '6281399887766', '1000000', '500', 'success', 'BSI'],
                ['INV-202611-005', '2026-11-03 07:20:10', 'Ambulans Darurat Khan Younis', 'Fikri Haikal', '082155443322', '50000', '12', 'waiting', 'BRI'],
            ];

            foreach ($sampleRows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function downloadBlastTemplate()
    {
        $filename = "template_riwayat_blast_everpro_harpa.csv";
        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
            fputcsv($handle, [
                'receiver_number',
                'broadcast_id',
                'message_id',
                'status',
                'timestamp',
            ]);

            $sampleRows = [
                ['6281234567890', 'SELLING_JUMAT_20261106', 'MSG-EVR-001', 'READ', '2026-11-06 09:15:30'],
                ['6285712345678', 'SELLING_JUMAT_20261106', 'MSG-EVR-002', 'DELIVERED', '2026-11-06 09:15:32'],
                ['6281987654321', 'SELLING_JUMAT_20261106', 'MSG-EVR-003', 'FAILED', '2026-11-06 09:15:35'],
                ['6281399887766', 'PAYDAY_20261101', 'MSG-EVR-004', 'READ', '2026-11-01 10:00:12'],
                ['6282155443322', 'PERKENALAN_DONATUR_202611', 'MSG-EVR-005', 'DELIVERED', '2026-11-02 11:30:00'],
            ];

            foreach ($sampleRows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function downloadWbsTemplate()
    {
        $filename = "template_hasil_wbs_pro_harpa.csv";
        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
            fputcsv($handle, [
                'no_hp',
                'status',
            ]);

            $sampleRows = [
                ['6281234567890', 'Aktif'],
                ['6285712345678', 'Aktif'],
                ['6281987654321', 'Mati / Tidak Aktif'],
                ['6281399887766', 'Invalid Number'],
                ['6282155443322', 'Bukan WhatsApp'],
                ['089912345678', 'Mati'],
            ];

            foreach ($sampleRows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * ALUR F: Laporan analitik donatur.
     */
    public function laporanAnalitik(Request $request)
    {
        $tab = $request->query('tab', 'hitung_donatur');

        // Jika master donatur masih kosong, otomatis sinkronkan dari data donasi web
        if (\App\Models\Harpa\MasterDonatur::count() === 0) {
            $this->engine->syncFromWebDonations();
        }

        $hitungDonatur = ($tab === 'hitung_donatur') ? $this->engine->getLaporanHitungDonatur() : [];
        $kohort = ($tab === 'kohort') ? $this->engine->getLaporanKohort() : [];
        $donaturTakBaca = ($tab === 'donatur_tak_baca') ? $this->engine->getLaporanDonaturTakBaca(
            $request->query('start_date'),
            $request->query('end_date')
        ) : [];

        $mintaNomorBaru = ($tab === 'minta_nomor_baru') ? $this->engine->getLaporanMintaNomorBaru(
            $request->filled('min_donasi') ? (float)$request->query('min_donasi') : null,
            $request->query('search')
        ) : [];

        $trackingBulanan = ($tab === 'tracking_bulanan') ? $this->engine->getLaporanTrackingBulanan(
            $request->filled('year') ? (int)$request->query('year') : null,
            $request->query('search')
        ) : [];

        $trackingJumat = ($tab === 'tracking_jumat') ? $this->engine->getLaporanTrackingJumat(
            $request->filled('year') ? (int)$request->query('year') : null,
            $request->filled('month') ? (int)$request->query('month') : null,
            $request->query('search')
        ) : [];

        return view('admin.harpa.laporan_analitik', [
            'active'          => 'harpa_laporan',
            'tab'             => $tab,
            'hitungDonatur'   => $hitungDonatur,
            'kohort'          => $kohort,
            'donaturTakBaca'  => $donaturTakBaca,
            'mintaNomorBaru'  => $mintaNomorBaru,
            'trackingBulanan' => $trackingBulanan,
            'trackingJumat'   => $trackingJumat,
            'startDate'       => $request->query('start_date'),
            'endDate'         => $request->query('end_date'),
            'search'          => $request->query('search'),
            'year'            => $request->query('year', (int)date('Y')),
            'month'           => $request->query('month', (int)date('m')),
            'minDonasi'       => $request->query('min_donasi'),
        ]);
    }

    /**
     * Memperbarui nomor telepon baru donatur (Minta Nomor Baru CRM).
     */
    public function updateNomorBaru(Request $request)
    {
        $request->validate([
            'donatur_id' => 'required|string',
            'no_hp_lama' => 'required|string',
            'nomor_baru' => 'required|string',
            'pic_crm'    => 'nullable|string|max:100',
            'catatan'    => 'nullable|string|max:255',
        ]);

        $donatur = \App\Models\Harpa\MasterDonatur::findOrFail($request->input('donatur_id'));
        $norm = \App\Models\Harpa\MasterNomor::normalize($request->input('nomor_baru'));

        if (!$norm['is_valid']) {
            return back()->with('error', 'Format nomor baru tidak valid. Pastikan nomor HP aktif diawali 08 atau 62.');
        }

        $cleanNewNumber = $norm['number'];
        $oldNumber = $request->input('no_hp_lama');
        $picCrm = $request->input('pic_crm') ?: 'Staff CRM';

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Perbarui data donatur
            $donatur->no_hp = $cleanNewNumber;
            if ($request->filled('pic_crm')) {
                $donatur->pic_prospek = $picCrm;
            }
            $donatur->save();

            // Tandai nomor lama sebagai non-aktif
            $oldMaster = \App\Models\Harpa\MasterNomor::find($oldNumber);
            if ($oldMaster) {
                $oldMaster->wa_aktif = false;
                $oldMaster->status_nomor = 'DEAD';
                $oldMaster->catatan = 'Digantikan oleh ' . $cleanNewNumber . ' (' . $picCrm . ')';
                $oldMaster->save();
            }

            // Daftarkan nomor baru
            \App\Models\Harpa\MasterNomor::updateOrCreate(
                ['no_hp' => $cleanNewNumber],
                [
                    'donatur_id'   => $donatur->donatur_id,
                    'status_nomor' => 'ACTIVE',
                    'wa_aktif'     => true,
                    'tgl_catat'    => now()->toDateString(),
                    'catatan'      => $request->input('catatan') ?: 'Nomor baru hasil reaktivasi CRM',
                ]
            );

            \Illuminate\Support\Facades\DB::commit();
            return back()->with('success', "Nomor donatur {$donatur->nama_lengkap} ({$donatur->donatur_id}) berhasil diperbarui menjadi {$cleanNewNumber}.");
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Gagal memperbarui nomor: ' . $e->getMessage());
        }
    }

    /**
     * Ekspor data laporan analitik ke file CSV.
     */
    public function exportLaporanAnalitik(Request $request, string $type)
    {
        $filename = "laporan_{$type}_" . date('Ymd_His') . ".csv";

        return response()->streamDownload(function () use ($type, $request) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM

            if ($type === 'minta_nomor_baru') {
                fputcsv($handle, ['no_hp_lama', 'donatur_id', 'total_donasi', 'nama_lengkap', 'nomor_baru', 'pic_crm', 'status_wa', 'donasi_terakhir', 'catatan']);
                $data = $this->engine->getLaporanMintaNomorBaru(
                    $request->filled('min_donasi') ? (float)$request->query('min_donasi') : null,
                    $request->query('search')
                );
                foreach ($data['items'] as $row) {
                    fputcsv($handle, [
                        $row['no_hp_lama'],
                        $row['donatur_id'],
                        $row['total_donasi'],
                        $row['nama_lengkap'],
                        $row['nomor_baru'],
                        $row['pic_crm'],
                        $row['status_wa'],
                        $row['donasi_terakhir'],
                        $row['catatan'],
                    ]);
                }
            } elseif ($type === 'tracking_bulanan') {
                $year = $request->query('year', (int)date('Y'));
                fputcsv($handle, ['donatur_id', 'nama_lengkap', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des', 'total', 'frekuensi', 'rata_rata', 'kategori', 'badge']);
                $data = $this->engine->getLaporanTrackingBulanan((int)$year, $request->query('search'));
                foreach ($data['items'] as $row) {
                    $m = $row['monthly'];
                    fputcsv($handle, [
                        $row['donatur_id'],
                        $row['nama_lengkap'],
                        $m[1], $m[2], $m[3], $m[4], $m[5], $m[6], $m[7], $m[8], $m[9], $m[10], $m[11], $m[12],
                        $row['total'],
                        $row['frekuensi'],
                        round($row['rata_rata']),
                        $row['kategori'],
                        $row['badge'],
                    ]);
                }
            } elseif ($type === 'tracking_jumat') {
                $year = $request->query('year', (int)date('Y'));
                $month = $request->query('month', (int)date('m'));
                fputcsv($handle, ['donatur_id', 'nama_lengkap', 'bulan', 'J1', 'J2', 'J3', 'J4', 'J5', 'total_bulan', 'jml_blast', 'jml_read', 'status_terakhir', 'catatan_crm']);
                $data = $this->engine->getLaporanTrackingJumat((int)$year, (int)$month, $request->query('search'));
                foreach ($data['items'] as $row) {
                    fputcsv($handle, [
                        $row['donatur_id'],
                        $row['nama_lengkap'],
                        $month,
                        $row['j1'],
                        $row['j2'],
                        $row['j3'],
                        $row['j4'],
                        $row['j5'],
                        $row['total_bulan'],
                        $row['jml_blast'],
                        $row['jml_read'],
                        $row['status_terakhir'],
                        $row['catatan_crm'],
                    ]);
                }
            } elseif ($type === 'hitung_donatur') {
                fputcsv($handle, ['donatur_id', 'nama_lengkap', 'no_hp', 'kategori', 'badge', 'segmen_rfm', 'frekuensi', 'total_donasi', 'donasi_terakhir', 'recency_hari']);
                foreach ($this->engine->getLaporanHitungDonatur() as $row) {
                    fputcsv($handle, [
                        $row['donatur_id'],
                        $row['nama_lengkap'],
                        $row['no_hp'],
                        $row['kategori'],
                        $row['badge'],
                        $row['segmen_rfm'],
                        $row['frekuensi'],
                        $row['total_donasi'],
                        $row['donasi_terakhir'],
                        $row['days_since_last'],
                    ]);
                }
            } elseif ($type === 'kohort') {
                $headers = ['bulan_gabung', 'jml_donatur'];
                for ($m = 0; $m <= 11; $m++) {
                    $headers[] = "M+{$m} (%)";
                }
                fputcsv($handle, $headers);
                foreach ($this->engine->getLaporanKohort() as $month => $info) {
                    $line = [$month, $info['cohort_size']];
                    for ($m = 0; $m <= 11; $m++) {
                        $activeCount = $info['months'][$m] ?? 0;
                        $pct = $info['cohort_size'] > 0 ? round(($activeCount / $info['cohort_size']) * 100, 1) : 0;
                        $line[] = $activeCount > 0 ? "{$pct}%" : '-';
                    }
                    fputcsv($handle, $line);
                }
            } elseif ($type === 'donatur_tak_baca') {
                fputcsv($handle, ['donatur_id', 'nama_lengkap', 'no_hp', 'total_donasi', 'frekuensi', 'tgl_donasi_terakhir', 'jml_blast', 'read_blast', 'catatan_analisis']);
                $rows = $this->engine->getLaporanDonaturTakBaca(
                    $request->query('start_date'),
                    $request->query('end_date')
                );
                foreach ($rows as $row) {
                    fputcsv($handle, [
                        $row['donatur_id'],
                        $row['nama_lengkap'],
                        $row['no_hp'],
                        $row['total_donasi'],
                        $row['frekuensi'],
                        $row['donasi_terakhir'] ?: '-',
                        $row['total_blast'],
                        $row['read_blast'],
                        $row['catatan'],
                    ]);
                }
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Ekspor Workbook Lengkap Multi-Sheet (.xlsx) sesuai 3 Spreadsheet Acuan:
     * - induk: HARPA_DB_INDUK (20 sheet)
     * - blast: DB_BLAST (19 sheet)
     * - analitik: HARPA_ANALITIK (14 sheet)
     */
    public function exportWorkbook(Request $request, string $workbook)
    {
        $exporter = app(\App\Services\Harpa\HarpaSpreadsheetExportService::class);
        $stamp = date('Ymd_His');

        switch (strtolower($workbook)) {
            case 'induk':
                $binary = $exporter->generateDbIndukWorkbook();
                $filename = "HARPA_DB_INDUK_{$stamp}.xlsx";
                break;

            case 'blast':
                $binary = $exporter->generateDbBlastWorkbook();
                $filename = "HARPA_DB_BLAST_{$stamp}.xlsx";
                break;

            case 'analitik':
                $year = $request->filled('year') ? (int) $request->query('year') : null;
                $month = $request->filled('month') ? (int) $request->query('month') : null;
                $binary = $exporter->generateAnalitikWorkbook(
                    $year,
                    $month,
                    $request->query('start_date'),
                    $request->query('end_date')
                );
                $filename = "HARPA_ANALITIK_{$stamp}.xlsx";
                break;

            default:
                abort(404, 'Format workbook tidak ditemukan.');
        }

        return response($binary, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'max-age=0',
        ]);
    }

    /**
     * Ekspor CSV Rekap Segmen Waterfall (Alur C).
     */
    public function exportRekapSegmen(Request $request)
    {
        $segmen = $request->query('segmen');
        $search = $request->query('search');
        $data = $this->engine->getRekapSegments($segmen ?: null, $search ?: null);
        $filename = 'harpa_rekap_segmen_' . date('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, ['no_hp', 'donatur_id', 'nama', 'segmen', 'jadwal', 'alasan', 'total_blast', 'read', 'delivered', 'success', 'failed', 'total_donasi', 'donasi_count', 'tgl_donasi_terakhir', 'wa_aktif']);
            foreach ($data as $r) {
                fputcsv($handle, [
                    $r['no_hp'],
                    $r['donatur_id'],
                    $r['nama'],
                    $r['segmen'],
                    $r['jadwal'],
                    $r['alasan'],
                    $r['total_blast'],
                    $r['read'],
                    $r['delivered'],
                    $r['success'],
                    $r['failed'],
                    $r['total_donasi'],
                    $r['donasi_count'],
                    $r['tgl_donasi_terakhir'] ? \Carbon\Carbon::parse($r['tgl_donasi_terakhir'])->toDateString() : '-',
                    $r['wa_aktif'] ? 'Aktif' : 'Mati',
                ]);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * Ekspor CSV Riwayat Blast Everpro (Alur B).
     */
    public function exportBlastHistory(Request $request)
    {
        $filename = 'harpa_riwayat_blast_' . date('Ymd_His') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, ['receiver_number', 'broadcast_id', 'message_id', 'status', 'timestamp_blast', 'batch_tarik']);
            foreach (RawBlast::orderBy('timestamp_blast', 'desc')->cursor() as $rb) {
                fputcsv($handle, [
                    $rb->receiver_number,
                    $rb->broadcast_id,
                    $rb->message_id,
                    $rb->status,
                    $rb->timestamp_blast ? $rb->timestamp_blast->format('Y-m-d H:i:s') : '',
                    $rb->batch_tarik ?: '',
                ]);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * Ekspor CSV Nomor Mati WBS Pro (Alur E).
     */
    public function exportWbsPro(Request $request)
    {
        $filename = 'harpa_nomor_mati_wbs_' . date('Ymd_His') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, ['no_hp', 'is_wa_aktif', 'tgl_cek', 'catatan', 'batch_tarik']);
            foreach (RawCekWa::orderBy('tgl_cek', 'desc')->cursor() as $cw) {
                fputcsv($handle, [
                    $cw->no_hp,
                    $cw->is_wa_aktif ? 1 : 0,
                    $cw->tgl_cek ? \Carbon\Carbon::parse($cw->tgl_cek)->toDateString() : '',
                    $cw->catatan ?: '',
                    $cw->batch_tarik ?: '',
                ]);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * Trigger manual sinkronisasi data donasi web ke sistem data Harpa.
     */
    public function syncWebDonations(Request $request)
    {
        $count = $this->engine->syncFromWebDonations();
        return back()->with('success', "Berhasil menyinkronkan {$count} transaksi donasi dari website ke Sistem Data Harpa.");
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

