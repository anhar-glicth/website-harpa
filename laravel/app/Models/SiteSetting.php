<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $table = 'site_settings';
    protected $fillable = [
        'hero_title',
        'hero_subtitle',
        'hero_image',
        'about_mission',
        'about_image',
        'impact_title',
        'impact_image',
    ];
}
