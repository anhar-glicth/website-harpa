<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('marketing_ads_data')) {
            Schema::create('marketing_ads_data', function (Blueprint $table) {
                $table->id();
                $table->date('date')->index(); // Tanggal penayangan / pencatatan
                $table->string('platform', 50)->default('Meta Ads')->index(); // Meta Ads, Google Ads, TikTok Ads, dll
                $table->string('campaign_name', 255)->index(); // Nama Campaign di Ads Manager
                $table->string('adset_name', 255)->nullable(); // Target audiens / Ad Set
                $table->string('ad_name', 255)->nullable(); // Nama materi kreatif / visual
                $table->foreignId('campaign_id')->nullable()->constrained('campaigns')->nullOnDelete(); // Hubungkan ke campaign web
                $table->decimal('spend', 15, 2)->default(0); // Biaya Iklan (Rp)
                $table->bigInteger('impressions')->default(0); // Tayangan
                $table->bigInteger('reach')->default(0); // Jangkauan unik
                $table->bigInteger('clicks')->default(0); // Klik tautan (Link Clicks)
                $table->decimal('cpc', 12, 2)->default(0); // Cost per Click (Rp)
                $table->decimal('ctr', 8, 2)->default(0); // Click-Through Rate (%)
                $table->decimal('cpm', 12, 2)->default(0); // Cost per Mille (Rp)
                $table->integer('conversions')->default(0); // Hasil Leads / Donatur baru
                $table->decimal('cost_per_conversion', 15, 2)->default(0); // Biaya per Donatur / CPA (Rp)
                $table->decimal('donation_amount', 15, 2)->default(0); // Total donasi diatribusikan (Rp)
                $table->decimal('roas', 8, 2)->default(0); // Return on Ad Spend (donasi / spend)
                $table->string('status', 50)->default('active'); // active, paused, completed
                $table->string('decision', 50)->default('MAINTAIN')->index(); // SCALE, MAINTAIN, STOP
                $table->text('notes')->nullable(); // Catatan analisa & evaluasi
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing_ads_data');
    }
};

