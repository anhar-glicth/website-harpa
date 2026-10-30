<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketingAdsData extends Model
{
    protected $table = 'marketing_ads_data';

    protected $fillable = [
        'date',
        'platform',
        'campaign_name',
        'adset_name',
        'ad_name',
        'campaign_id',
        'spend',
        'impressions',
        'reach',
        'clicks',
        'cpc',
        'ctr',
        'cpm',
        'conversions',
        'cost_per_conversion',
        'donation_amount',
        'roas',
        'status',
        'decision',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'date'                => 'date',
        'spend'               => 'decimal:2',
        'impressions'         => 'integer',
        'reach'               => 'integer',
        'clicks'              => 'integer',
        'cpc'                 => 'decimal:2',
        'ctr'                 => 'decimal:2',
        'cpm'                 => 'decimal:2',
        'conversions'         => 'integer',
        'cost_per_conversion' => 'decimal:2',
        'donation_amount'     => 'decimal:2',
        'roas'                => 'decimal:2',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Hitung otomatis metrik CPC, CTR, CPM, CPA, ROAS, dan Rekomendasi Keputusan sebelum disimpan.
     */
    protected static function booted()
    {
        static::saving(function ($model) {
            $spend = (float) $model->spend;
            $impressions = (int) $model->impressions;
            $clicks = (int) $model->clicks;
            $conversions = (int) $model->conversions;
            $donation = (float) $model->donation_amount;

            // CPC: Cost per Click
            $model->cpc = ($clicks > 0) ? round($spend / $clicks, 2) : 0;

            // CTR: Click-Through Rate (%)
            $model->ctr = ($impressions > 0) ? round(($clicks / $impressions) * 100, 2) : 0;

            // CPM: Cost per 1,000 Impressions
            $model->cpm = ($impressions > 0) ? round(($spend / $impressions) * 1000, 2) : 0;

            // CPA: Cost per Conversion / Lead
            $model->cost_per_conversion = ($conversions > 0) ? round($spend / $conversions, 2) : 0;

            // ROAS: Return on Ad Spend (Donation Amount / Spend)
            $model->roas = ($spend > 0) ? round($donation / $spend, 2) : 0;

            // Rekomendasi Keputusan Otomatis jika belum ditentukan secara manual
            if (empty($model->decision) || $model->decision === 'AUTO') {
                if ($model->roas >= 3.0 || ($conversions >= 5 && $model->cost_per_conversion <= 50000 && $model->cost_per_conversion > 0)) {
                    $model->decision = 'SCALE'; // Hijau: Naikkan budget / Scale Up
                } elseif ($spend >= 100000 && ($model->roas < 1.0 && $model->ctr < 0.8)) {
                    $model->decision = 'STOP'; // Merah: Matikan iklan / ganti kreatif
                } else {
                    $model->decision = 'MAINTAIN'; // Kuning: Pertahankan & optimasi
                }
            }
        });
    }
}

