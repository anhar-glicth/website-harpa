<?php

namespace App\Models\Harpa;

use App\Models\Campaign;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RefCampaignAlias extends Model
{
    protected $table = 'ref_campaign_alias';

    protected $fillable = [
        'teks_asli',
        'campaign_id',
        'nama_campaign_standar',
        'program_id',
        'catatan',
    ];

    /**
     * Relasi ke campaign resmi di website.
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'campaign_id', 'id');
    }

    /**
     * Cari pemetaan campaign_id dari teks asli judul ekspor.
     */
    public static function resolveCampaign(string $rawTitle): ?self
    {
        $normalized = trim($rawTitle);
        return static::where('teks_asli', $normalized)->first();
    }
}

