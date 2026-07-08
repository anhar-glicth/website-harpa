<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('stats_beneficiaries', 100)->nullable()->default('2.500 +');
            $table->string('stats_projects', 100)->nullable()->default('95 +');
            $table->string('stats_volunteers', 100)->nullable()->default('20 +');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['stats_beneficiaries', 'stats_projects', 'stats_volunteers']);
        });
    }
};
