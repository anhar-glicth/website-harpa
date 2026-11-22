<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\MarketingAdsData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MarketingController extends Controller
{
    /**
     * Dashboard Analisa Marketing & Meta Ads
     * Menampilkan KPI, Bar Chart (Spend vs Donasi), Pie/Donut Chart, dan Rekomendasi Keputusan (SCALE / MAINTAIN / STOP).
     */
    public function dashboard(Request $request)
    {
        $platform = $request->query('platform');
        $program = $request->query('program');
        $dateFrom = $request->query('date_from', now()->subDays(30)->toDateString());
        $dateTo = $request->query('date_to', now()->toDateString());
        $campaignId = $request->query('campaign_id');

        $query = MarketingAdsData::query();

        if ($platform && $platform !== 'ALL') {
            $query->where('platform', $platform);
        }

        if ($program && $program !== 'ALL') {
            $query->where('program_name', $program);
        }

        if ($dateFrom) {
            $query->whereDate('date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('date', '<=', $dateTo);
        }

        if ($campaignId) {
            $query->where('campaign_id', $campaignId);
        }

        // Clone query untuk kalkulasi agregat KPI
        $kpiQuery = clone $query;
        $totalSpend = (float) $kpiQuery->sum('spend');
        $totalDonations = (float) $kpiQuery->sum('donation_amount');
        $totalImpressions = (int) $kpiQuery->sum('impressions');
        $totalReach = (int) $kpiQuery->sum('reach');
        $totalClicks = (int) $kpiQuery->sum('clicks');
        $totalConversions = (int) $kpiQuery->sum('conversions');

        $overallRoas = $totalSpend > 0 ? round($totalDonations / $totalSpend, 2) : 0;
        $overallCtr = $totalImpressions > 0 ? round(($totalClicks / $totalImpressions) * 100, 2) : 0;
        $avgCpc = $totalClicks > 0 ? round($totalSpend / $totalClicks, 2) : 0;
        $avgCpa = $totalConversions > 0 ? round($totalSpend / $totalConversions, 2) : 0;
        $avgCpm = $totalImpressions > 0 ? round(($totalSpend / $totalImpressions) * 1000, 2) : 0;

        // Count Decisions
        $scaleCount = (clone $query)->where('decision', 'SCALE')->count();
        $maintainCount = (clone $query)->where('decision', 'MAINTAIN')->count();
        $stopCount = (clone $query)->where('decision', 'STOP')->count();

        // 1. Data Diagram Batang (Bar Chart): Spend vs Donasi per Tanggal
        $barDataRaw = (clone $query)
            ->select(
                DB::raw('DATE(date) as ad_date'),
                DB::raw('SUM(spend) as total_spend'),
                DB::raw('SUM(donation_amount) as total_donation'),
                DB::raw('SUM(conversions) as total_conversions')
            )
            ->groupBy('ad_date')
            ->orderBy('ad_date', 'ASC')
            ->get();

        $barDates = [];
        $barSpends = [];
        $barDonations = [];
        $barConversions = [];

        foreach ($barDataRaw as $row) {
            $barDates[] = date('d M', strtotime($row->ad_date));
            $barSpends[] = (float) $row->total_spend;
            $barDonations[] = (float) $row->total_donation;
            $barConversions[] = (int) $row->total_conversions;
        }

        // 2. Data Pie/Donut Chart 1: Porsi Spend Berdasarkan Campaign
        $pieCampaignRaw = (clone $query)
            ->select('campaign_name', DB::raw('SUM(spend) as total_spend'))
            ->groupBy('campaign_name')
            ->orderByDesc('total_spend')
            ->limit(6)
            ->get();

        $pieCampaignLabels = [];
        $pieCampaignData = [];
        foreach ($pieCampaignRaw as $row) {
            $pieCampaignLabels[] = $row->campaign_name ?: 'Tanpa Judul';
            $pieCampaignData[] = (float) $row->total_spend;
        }

        // 3. Data Pie/Donut Chart 2: Porsi Spend Berdasarkan Platform
        $piePlatformRaw = (clone $query)
            ->select('platform', DB::raw('SUM(spend) as total_spend'), DB::raw('SUM(donation_amount) as total_donation'))
            ->groupBy('platform')
            ->orderByDesc('total_spend')
            ->get();

        $piePlatformLabels = [];
        $piePlatformData = [];
        foreach ($piePlatformRaw as $row) {
            $piePlatformLabels[] = $row->platform;
            $piePlatformData[] = (float) $row->total_spend;
        }

        // 4. Rekomendasi Keputusan Penting (SCALE / STOP highlight)
        $scaleHighlights = (clone $query)->where('decision', 'SCALE')->orderByDesc('roas')->limit(5)->get();
        $stopHighlights = (clone $query)->where('decision', 'STOP')->orderByDesc('spend')->limit(5)->get();

        // 5. Data Iklan Terbaru
        $recentAds = (clone $query)->with('campaign')->orderByDesc('date')->orderByDesc('id')->limit(8)->get();

        $allCampaigns = Campaign::select('id', 'title')->orderBy('title')->get();
        $programsList = MarketingAdsData::whereNotNull('program_name')->where('program_name', '!=', '')->distinct()->orderBy('program_name')->pluck('program_name');

        return view('admin.marketing.dashboard', compact(
            'totalSpend',
            'totalDonations',
            'totalImpressions',
            'totalReach',
            'totalClicks',
            'totalConversions',
            'overallRoas',
            'overallCtr',
            'avgCpc',
            'avgCpa',
            'avgCpm',
            'scaleCount',
            'maintainCount',
            'stopCount',
            'barDates',
            'barSpends',
            'barDonations',
            'barConversions',
            'pieCampaignLabels',
            'pieCampaignData',
            'piePlatformLabels',
            'piePlatformData',
            'scaleHighlights',
            'stopHighlights',
            'recentAds',
            'allCampaigns',
            'platform',
            'program',
            'programsList',
            'dateFrom',
            'dateTo',
            'campaignId'
        ));
    }

    /**
     * Tabel List & Filter Data Marketing Ads
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $platform = $request->query('platform');
        $decision = $request->query('decision');
        $status = $request->query('status');
        $program = $request->query('program');
        $pic = $request->query('pic');
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');

        $query = MarketingAdsData::query()->with('campaign', 'creator');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('campaign_name', 'like', "%{$search}%")
                  ->orWhere('adset_name', 'like', "%{$search}%")
                  ->orWhere('ad_name', 'like', "%{$search}%")
                  ->orWhere('account_name', 'like', "%{$search}%")
                  ->orWhere('program_name', 'like', "%{$search}%")
                  ->orWhere('pic', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        if ($platform && $platform !== 'ALL') {
            $query->where('platform', $platform);
        }

        if ($decision && $decision !== 'ALL') {
            $query->where('decision', $decision);
        }

        if ($status && $status !== 'ALL') {
            $query->where('status', $status);
        }

        if ($program && $program !== 'ALL') {
            $query->where('program_name', $program);
        }

        if ($pic && $pic !== 'ALL') {
            $query->where('pic', $pic);
        }

        if ($dateFrom) {
            $query->whereDate('date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('date', '<=', $dateTo);
        }

        $ads = $query->orderByDesc('date')->orderByDesc('id')->paginate(15)->withQueryString();

        $programsList = MarketingAdsData::whereNotNull('program_name')->where('program_name', '!=', '')->distinct()->orderBy('program_name')->pluck('program_name');
        $picsList = MarketingAdsData::whereNotNull('pic')->where('pic', '!=', '')->distinct()->orderBy('pic')->pluck('pic');

        return view('admin.marketing.index', compact(
            'ads', 'search', 'platform', 'decision', 'status', 'program', 'pic', 'dateFrom', 'dateTo', 'programsList', 'picsList'
        ));
    }

    /**
     * Form Input Data Iklan Baru
     */
    public function create()
    {
        $campaigns = Campaign::select('id', 'title')->orderBy('title')->get();
        return view('admin.marketing.create', compact('campaigns'));
    }

    /**
     * Simpan Data Iklan Baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date'               => 'required|date',
            'platform'           => 'required|string|max:50',
            'campaign_name'      => 'required|string|max:255',
            'adset_name'         => 'nullable|string|max:255',
            'ad_name'            => 'nullable|string|max:255',
            'account_name'       => 'nullable|string|max:255',
            'program_name'       => 'nullable|string|max:255',
            'pic'                => 'nullable|string|max:100',
            'age'                => 'nullable|string|max:50',
            'gender'             => 'nullable|string|max:20',
            'campaign_id'        => 'nullable|exists:campaigns,id',
            'spend'              => 'required|numeric|min:0',
            'impressions'        => 'nullable|integer|min:0',
            'reach'              => 'nullable|integer|min:0',
            'clicks'             => 'nullable|integer|min:0',
            'landing_page_views' => 'nullable|integer|min:0',
            'add_to_cart'        => 'nullable|integer|min:0',
            'conversions'        => 'nullable|integer|min:0',
            'donation_amount'    => 'nullable|numeric|min:0',
            'status'             => 'required|in:ACTIVE,PAUSED,ARCHIVED',
            'decision'           => 'nullable|in:AUTO,SCALE,MAINTAIN,STOP',
            'notes'              => 'nullable|string',
        ]);

        $validated['impressions'] = $validated['impressions'] ?? 0;
        $validated['reach'] = $validated['reach'] ?? 0;
        $validated['clicks'] = $validated['clicks'] ?? 0;
        $validated['landing_page_views'] = $validated['landing_page_views'] ?? 0;
        $validated['add_to_cart'] = $validated['add_to_cart'] ?? 0;
        $validated['conversions'] = $validated['conversions'] ?? 0;
        $validated['donation_amount'] = $validated['donation_amount'] ?? 0;
        $validated['created_by'] = Auth::id();

        MarketingAdsData::create($validated);

        return redirect()->route('admin.marketing.index')->with('success', 'Data iklan marketing berhasil disimpan dan metrik performa telah dihitung otomatis!');
    }

    /**
     * Form Edit Data Iklan
     */
    public function edit($id)
    {
        $ad = MarketingAdsData::findOrFail($id);
        $campaigns = Campaign::select('id', 'title')->orderBy('title')->get();
        return view('admin.marketing.edit', compact('ad', 'campaigns'));
    }

    /**
     * Update Data Iklan
     */
    public function update(Request $request, $id)
    {
        $ad = MarketingAdsData::findOrFail($id);

        $validated = $request->validate([
            'date'               => 'required|date',
            'platform'           => 'required|string|max:50',
            'campaign_name'      => 'required|string|max:255',
            'adset_name'         => 'nullable|string|max:255',
            'ad_name'            => 'nullable|string|max:255',
            'account_name'       => 'nullable|string|max:255',
            'program_name'       => 'nullable|string|max:255',
            'pic'                => 'nullable|string|max:100',
            'age'                => 'nullable|string|max:50',
            'gender'             => 'nullable|string|max:20',
            'campaign_id'        => 'nullable|exists:campaigns,id',
            'spend'              => 'required|numeric|min:0',
            'impressions'        => 'nullable|integer|min:0',
            'reach'              => 'nullable|integer|min:0',
            'clicks'             => 'nullable|integer|min:0',
            'landing_page_views' => 'nullable|integer|min:0',
            'add_to_cart'        => 'nullable|integer|min:0',
            'conversions'        => 'nullable|integer|min:0',
            'donation_amount'    => 'nullable|numeric|min:0',
            'status'             => 'required|in:ACTIVE,PAUSED,ARCHIVED',
            'decision'           => 'nullable|in:AUTO,SCALE,MAINTAIN,STOP',
            'notes'              => 'nullable|string',
        ]);

        $validated['impressions'] = $validated['impressions'] ?? 0;
        $validated['reach'] = $validated['reach'] ?? 0;
        $validated['clicks'] = $validated['clicks'] ?? 0;
        $validated['landing_page_views'] = $validated['landing_page_views'] ?? 0;
        $validated['add_to_cart'] = $validated['add_to_cart'] ?? 0;
        $validated['conversions'] = $validated['conversions'] ?? 0;
        $validated['donation_amount'] = $validated['donation_amount'] ?? 0;

        $ad->update($validated);

        return redirect()->route('admin.marketing.index')->with('success', 'Data iklan marketing berhasil diperbarui!');
    }

    /**
     * Hapus Data Iklan
     */
    public function destroy($id)
    {
        $ad = MarketingAdsData::findOrFail($id);
        $ad->delete();

        return redirect()->route('admin.marketing.index')->with('success', 'Data iklan berhasil dihapus.');
    }

    /**
     * Export CSV Data Marketing Ads
     */
    public function export(Request $request): StreamedResponse
    {
        $platform = $request->query('platform');
        $decision = $request->query('decision');
        $status = $request->query('status');
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');

        $query = MarketingAdsData::query()->with('campaign');

        if ($platform && $platform !== 'ALL') {
            $query->where('platform', $platform);
        }

        if ($decision && $decision !== 'ALL') {
            $query->where('decision', $decision);
        }

        if ($status && $status !== 'ALL') {
            $query->where('status', $status);
        }

        if ($dateFrom) {
            $query->whereDate('date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('date', '<=', $dateTo);
        }

        $filename = 'marketing_ads_export_' . date('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8

            fputcsv($handle, [
                'Tanggal', 'Platform', 'PIC', 'Usia', 'Jenis Kelamin', 'Program',
                'Nama Campaign Ads', 'Adset', 'Nama Iklan', 'Nama Akun',
                'Campaign Web', 'Biaya Iklan (Spend)', 'Impressions', 'Reach', 'Clicks',
                'Landing Page Views', 'Add to Cart', 'CPC', 'CTR (%)', 'CPM',
                'Conversions (Pembelian)', 'CPA', 'Hasil Donasi', 'ROAS', 'Status', 'Keputusan', 'Catatan'
            ]);

            $query->orderBy('date', 'ASC')->chunk(500, function ($rows) use ($handle) {
                foreach ($rows as $r) {
                    fputcsv($handle, [
                        $r->date ? $r->date->format('Y-m-d') : '',
                        $r->platform,
                        $r->pic ?? '-',
                        $r->age ?? '-',
                        $r->gender ?? '-',
                        $r->program_name ?? '-',
                        $r->campaign_name,
                        $r->adset_name ?? '-',
                        $r->ad_name ?? '-',
                        $r->account_name ?? '-',
                        $r->campaign ? $r->campaign->title : '-',
                        $r->spend,
                        $r->impressions,
                        $r->reach,
                        $r->clicks,
                        $r->landing_page_views,
                        $r->add_to_cart,
                        $r->cpc,
                        $r->ctr . '%',
                        $r->cpm,
                        $r->conversions,
                        $r->cost_per_conversion,
                        $r->donation_amount,
                        $r->roas . 'x',
                        $r->status,
                        $r->decision,
                        $r->notes ?? ''
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Import CSV Data Performa Marketing Ads
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:51200',
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();

        if (!file_exists($path) || !is_readable($path)) {
            return redirect()->back()->with('error', 'File CSV tidak dapat dibaca.');
        }

        set_time_limit(300);
        ini_set('memory_limit', '512M');

        $handle = fopen($path, 'r');
        if (!$handle) {
            return redirect()->back()->with('error', 'Gagal membuka file CSV.');
        }

        // Check BOM
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        // Delimiter auto-detection
        $sampleLine = fgets($handle);
        rewind($handle);
        if ($bom === "\xEF\xBB\xBF") {
            fread($handle, 3);
        }

        $delimiter = ',';
        if (substr_count($sampleLine, ';') > substr_count($sampleLine, ',')) {
            $delimiter = ';';
        }

        $rawHeaders = fgetcsv($handle, 0, $delimiter);
        if (!$rawHeaders) {
            fclose($handle);
            return redirect()->back()->with('error', 'File CSV kosong atau format header tidak valid.');
        }

        // Normalize headers
        $headers = array_map(function ($h) {
            return strtolower(trim(str_replace(["\xEF\xBB\xBF", '"', "'"], '', $h)));
        }, $rawHeaders);

        // Alias map
        $headerMap = [
            'date'               => ['date', 'tanggal', 'tgl', 'hari'],
            'pic'                => ['pic', 'pj', 'penanggung_jawab', 'operator'],
            'age'                => ['usia', 'age', 'umur'],
            'gender'             => ['jenis kelamin', 'jenis_kelamin', 'gender', 'jk', 'sex'],
            'program_name'       => ['program', 'nama program', 'nama_program', 'program_donasi'],
            'campaign_name'      => ['nama kampanye', 'campaign_name', 'nama_kampanye', 'kampanye', 'campaign'],
            'adset_name'         => ['nama set iklan', 'adset_name', 'nama_set_iklan', 'set iklan', 'adset', 'ad set'],
            'ad_name'            => ['nama iklan', 'ad_name', 'nama_iklan', 'iklan', 'ad', 'creative'],
            'account_name'       => ['nama akun', 'account_name', 'nama_akun', 'akun', 'account'],
            'spend'              => ['jumlah yang dibelanjakan (idr)', 'jumlah yang dibelanjakan', 'spend', 'biaya', 'biaya iklan', 'amount spent'],
            'donation_amount'    => ['nilai konversi pembelian', 'purchase conversion value', 'hasil donasi', 'donasi', 'donations', 'revenue', 'omset'],
            'reach'              => ['jangkauan', 'reach'],
            'impressions'        => ['impresi', 'impressions', 'tayangan'],
            'clicks'             => ['klik tautan', 'link clicks', 'clicks', 'klik'],
            'landing_page_views' => ['tayangan halaman landas', 'landing page views', 'landing_page_views', 'lp views', 'lp_views'],
            'add_to_cart'        => ['tambahkan ke keranjang', 'add to cart', 'add_to_cart', 'atc'],
            'conversions'        => ['pembelian', 'purchases', 'conversions', 'konversi', 'leads'],
            'platform'           => ['platform', 'channel', 'media'],
        ];

        $colIndex = [];
        foreach ($headerMap as $field => $aliases) {
            foreach ($headers as $idx => $headerText) {
                if (in_array($headerText, $aliases)) {
                    $colIndex[$field] = $idx;
                    break;
                }
            }
        }

        if (!isset($colIndex['campaign_name']) && isset($colIndex['program_name'])) {
            $colIndex['campaign_name'] = $colIndex['program_name'];
        }

        if (!isset($colIndex['campaign_name']) && !isset($colIndex['date'])) {
            fclose($handle);
            return redirect()->back()->with('error', 'Format header CSV tidak dikenali. Pastikan kolom Date, Nama Kampanye, atau Program ada di file.');
        }

        $campaignsMap = Campaign::pluck('id', 'title')->toArray();
        $userId = Auth::id();
        $batch = [];
        $importedCount = 0;
        $batchSize = 500;
        $now = now()->toDateTimeString();

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                if (empty($row) || count($row) < 2) continue;

                $getVal = function ($field, $default = '') use ($row, $colIndex) {
                    if (isset($colIndex[$field]) && isset($row[$colIndex[$field]])) {
                        return trim($row[$colIndex[$field]]);
                    }
                    return $default;
                };

                $parseNum = function ($field) use ($getVal) {
                    $raw = $getVal($field, '0');
                    if ($raw === '' || $raw === null) return 0;
                    $clean = preg_replace('/[^\d.,-]/', '', $raw);
                    if (str_contains($clean, ',') && !str_contains($clean, '.')) {
                        $clean = str_replace(',', '.', $clean);
                    } else {
                        $clean = str_replace(',', '', $clean);
                    }
                    return is_numeric($clean) ? (float) $clean : 0;
                };

                $dateRaw = $getVal('date');
                if (!$dateRaw && !$getVal('campaign_name')) continue;

                $date = null;
                if ($dateRaw) {
                    $parsed = strtotime($dateRaw);
                    if ($parsed) {
                        $date = date('Y-m-d', $parsed);
                    }
                }
                $date = $date ?: date('Y-m-d');

                $campaignName = $getVal('campaign_name') ?: ($getVal('program_name') ?: 'Meta Ads Campaign');
                $programName = $getVal('program_name') ?: null;
                $adsetName = $getVal('adset_name') ?: null;
                $adName = $getVal('ad_name') ?: null;
                $accountName = $getVal('account_name') ?: null;
                $pic = $getVal('pic') ?: null;
                $age = $getVal('age') ?: null;
                $gender = $getVal('gender') ?: null;
                $platform = $getVal('platform') ?: 'Meta Ads';

                $spend = $parseNum('spend');
                $donationAmount = $parseNum('donation_amount');
                $reach = (int) $parseNum('reach');
                $impressions = (int) $parseNum('impressions');
                $clicks = (int) $parseNum('clicks');
                $landingPageViews = (int) $parseNum('landing_page_views');
                $addToCart = (int) $parseNum('add_to_cart');
                $conversions = (int) $parseNum('conversions');

                // Metrik Terkalkulasi
                $cpc = ($clicks > 0) ? round($spend / $clicks, 2) : 0;
                $ctr = ($impressions > 0) ? round(($clicks / $impressions) * 100, 2) : 0;
                $cpm = ($impressions > 0) ? round(($spend / $impressions) * 1000, 2) : 0;
                $costPerConversion = ($conversions > 0) ? round($spend / $conversions, 2) : 0;
                $roas = ($spend > 0) ? round($donationAmount / $spend, 2) : 0;

                // Rekomendasi Keputusan Otomatis
                if ($roas >= 3.0 || ($conversions >= 5 && $costPerConversion <= 50000 && $costPerConversion > 0)) {
                    $decision = 'SCALE';
                } elseif ($spend >= 100000 && ($roas < 1.0 && $ctr < 0.8)) {
                    $decision = 'STOP';
                } else {
                    $decision = 'MAINTAIN';
                }

                // Hubungkan ke Campaign website bila ada judul yang cocok
                $campaignId = null;
                if ($programName && isset($campaignsMap[$programName])) {
                    $campaignId = $campaignsMap[$programName];
                } elseif (isset($campaignsMap[$campaignName])) {
                    $campaignId = $campaignsMap[$campaignName];
                }

                $batch[] = [
                    'date'                => $date,
                    'platform'            => $platform,
                    'campaign_name'       => mb_substr($campaignName, 0, 255),
                    'adset_name'          => $adsetName ? mb_substr($adsetName, 0, 255) : null,
                    'ad_name'             => $adName ? mb_substr($adName, 0, 255) : null,
                    'account_name'        => $accountName ? mb_substr($accountName, 0, 255) : null,
                    'program_name'        => $programName ? mb_substr($programName, 0, 255) : null,
                    'pic'                 => $pic ? mb_substr($pic, 0, 100) : null,
                    'age'                 => $age ? mb_substr($age, 0, 50) : null,
                    'gender'              => $gender ? mb_substr($gender, 0, 20) : null,
                    'campaign_id'         => $campaignId,
                    'spend'               => $spend,
                    'impressions'         => $impressions,
                    'reach'               => $reach,
                    'clicks'              => $clicks,
                    'landing_page_views'  => $landingPageViews,
                    'add_to_cart'         => $addToCart,
                    'cpc'                 => $cpc,
                    'ctr'                 => $ctr,
                    'cpm'                 => $cpm,
                    'conversions'         => $conversions,
                    'cost_per_conversion' => $costPerConversion,
                    'donation_amount'     => $donationAmount,
                    'roas'                => $roas,
                    'status'              => 'ACTIVE',
                    'decision'            => $decision,
                    'notes'               => 'Diimpor dari file CSV ' . date('d M Y H:i'),
                    'created_by'          => $userId,
                    'created_at'          => $now,
                    'updated_at'          => $now,
                ];

                $importedCount++;

                if (count($batch) >= $batchSize) {
                    MarketingAdsData::insert($batch);
                    $batch = [];
                }
            }

            if (!empty($batch)) {
                MarketingAdsData::insert($batch);
            }

            DB::commit();
            fclose($handle);

            return redirect()->route('admin.marketing.index')->with('success', "Berhasil mengimpor {$importedCount} baris data performa iklan Meta Ads!");
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses data: ' . $e->getMessage());
        }
    }

    /**
     * Download CSV Template untuk Import Iklan Meta Ads
     */
    public function downloadTemplate(): StreamedResponse
    {
        $filename = 'template_import_meta_ads.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8

            fputcsv($handle, [
                'Date', 'PIC', 'Usia', 'Jenis kelamin', 'Program', 'Nama Kampanye',
                'Nama Set Iklan', 'Nama Iklan', 'Nama Akun', 'Jumlah yang dibelanjakan (IDR)',
                'Nilai konversi pembelian', 'Jangkauan', 'Impresi', 'Klik Tautan',
                'Tayangan Halaman Landas', 'Tambahkan ke Keranjang', 'Pembelian'
            ]);

            fputcsv($handle, [
                date('Y-m-d'), 'Gus', '25-34', 'female', 'Adik Aiyla', 'ADIK AIYLA',
                'BROAD', 'Aiyla', '1340 Adspro - Amal Bakti Dunia Islam (ABADI)',
                '89383.00', '251942.00', '4172', '4589', '99.00', '92.00', '3.00', '3.00'
            ]);

            fputcsv($handle, [
                date('Y-m-d'), 'Gus', '18-24', 'female', 'Kek Dermawan', 'lansia - kek dermawan',
                'broad 25-65+', 'lansia', '1340 Adspro - Amal Bakti Dunia Islam (ABADI)',
                '1692.00', '0.00', '107', '107', '0.00', '0.00', '0.00', '0.00'
            ]);

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}

