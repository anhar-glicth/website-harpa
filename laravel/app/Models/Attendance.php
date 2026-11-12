<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    public const WITA_TIMEZONE = 'Asia/Makassar';

    protected $table = 'attendance';
    protected $fillable = ['user_id', 'type', 'location', 'latitude', 'longitude', 'attachment'];

    /**
     * Filter attendance rows whose created_at falls within today's date in WITA.
     */
    public function scopeForTodayWita(Builder $query): Builder
    {
        $appTz = config('app.timezone', self::WITA_TIMEZONE);
        $start = now(self::WITA_TIMEZONE)->startOfDay()->timezone($appTz);
        $end = now(self::WITA_TIMEZONE)->endOfDay()->timezone($appTz);

        return $query->whereBetween('created_at', [$start, $end]);
    }

    /**
     * Format created_at explicitly in WITA (UTC+8) regardless of server APP_TIMEZONE.
     */
    public function formatWita(string $format = 'H:i'): string
    {
        if (!$this->created_at) {
            return '-';
        }

        return $this->created_at->copy()->timezone(self::WITA_TIMEZONE)->format($format);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
