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
        $dateFrom = $request->query('date_from', now()->subDays(30)->toDateString());
        $dateTo = $request->query('date_to', now()->toDateString());
        $campaignId = $request->query('campaign_id');

        $query = MarketingAdsData::query();

        if ($platform && $platform !== 'ALL') {
            $query->where('platform', $platform);
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
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');

        $query = MarketingAdsData::query()->with('campaign', 'creator');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('campaign_name', 'like', "%{$search}%")
                  ->orWhere('adset_name', 'like', "%{$search}%")
                  ->orWhere('ad_name', 'like', "%{$search}%")
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

        if ($dateFrom) {
            $query->whereDate('date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('date', '<=', $dateTo);
        }

        $ads = $query->orderByDesc('date')->orderByDesc('id')->paginate(15)->withQueryString();

        return view('admin.marketing.index', compact('ads', 'search', 'platform', 'decision', 'status', 'dateFrom', 'dateTo'));
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
            'date'            => 'required|date',
            'platform'        => 'required|string|max:50',
            'campaign_name'   => 'required|string|max:255',
            'adset_name'      => 'nullable|string|max:255',
            'ad_name'         => 'nullable|string|max:255',
            'campaign_id'     => 'nullable|exists:campaigns,id',
            'spend'           => 'required|numeric|min:0',
            'impressions'     => 'nullable|integer|min:0',
            'reach'           => 'nullable|integer|min:0',
            'clicks'          => 'nullable|integer|min:0',
            'conversions'     => 'nullable|integer|min:0',
            'donation_amount' => 'nullable|numeric|min:0',
            'status'          => 'required|in:ACTIVE,PAUSED,ARCHIVED',
            'decision'        => 'nullable|in:AUTO,SCALE,MAINTAIN,STOP',
            'notes'           => 'nullable|string',
        ]);

        $validated['impressions'] = $validated['impressions'] ?? 0;
        $validated['reach'] = $validated['reach'] ?? 0;
        $validated['clicks'] = $validated['clicks'] ?? 0;
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
            'date'            => 'required|date',
            'platform'        => 'required|string|max:50',
            'campaign_name'   => 'required|string|max:255',
            'adset_name'      => 'nullable|string|max:255',
            'ad_name'         => 'nullable|string|max:255',
            'campaign_id'     => 'nullable|exists:campaigns,id',
            'spend'           => 'required|numeric|min:0',
            'impressions'     => 'nullable|integer|min:0',
            'reach'           => 'nullable|integer|min:0',
            'clicks'          => 'nullable|integer|min:0',
            'conversions'     => 'nullable|integer|min:0',
            'donation_amount' => 'nullable|numeric|min:0',
            'status'          => 'required|in:ACTIVE,PAUSED,ARCHIVED',
            'decision'        => 'nullable|in:AUTO,SCALE,MAINTAIN,STOP',
            'notes'           => 'nullable|string',
        ]);

        $validated['impressions'] = $validated['impressions'] ?? 0;
        $validated['reach'] = $validated['reach'] ?? 0;
        $validated['clicks'] = $validated['clicks'] ?? 0;
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
                'Tanggal', 'Platform', 'Nama Campaign Ads', 'Adset', 'Nama Iklan',
                'Campaign Web', 'Biaya Iklan (Spend)', 'Impressions', 'Reach', 'Clicks',
                'CPC', 'CTR (%)', 'CPM', 'Conversions (Leads)', 'CPA', 'Hasil Donasi',
                'ROAS', 'Status', 'Keputusan', 'Catatan'
            ]);

            $query->orderBy('date', 'ASC')->chunk(500, function ($rows) use ($handle) {
                foreach ($rows as $r) {
                    fputcsv($handle, [
                        $r->date ? $r->date->format('Y-m-d') : '',
                        $r->platform,
                        $r->campaign_name,
                        $r->adset_name ?? '-',
                        $r->ad_name ?? '-',
                        $r->campaign ? $r->campaign->title : '-',
                        $r->spend,
                        $r->impressions,
                        $r->reach,
                        $r->clicks,
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
}

