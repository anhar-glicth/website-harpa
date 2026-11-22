<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('marketing_ads_data', function (Blueprint $table) {
            if (!Schema::hasColumn('marketing_ads_data', 'account_name')) {
                $table->string('account_name', 255)->nullable()->after('ad_name');
            }
            if (!Schema::hasColumn('marketing_ads_data', 'program_name')) {
                $table->string('program_name', 255)->nullable()->after('account_name');
            }
            if (!Schema::hasColumn('marketing_ads_data', 'pic')) {
                $table->string('pic', 100)->nullable()->after('program_name');
            }
            if (!Schema::hasColumn('marketing_ads_data', 'age')) {
                $table->string('age', 50)->nullable()->after('pic');
            }
            if (!Schema::hasColumn('marketing_ads_data', 'gender')) {
                $table->string('gender', 20)->nullable()->after('age');
            }
            if (!Schema::hasColumn('marketing_ads_data', 'landing_page_views')) {
                $table->bigInteger('landing_page_views')->default(0)->after('clicks');
            }
            if (!Schema::hasColumn('marketing_ads_data', 'add_to_cart')) {
                $table->bigInteger('add_to_cart')->default(0)->after('landing_page_views');
            }
        });
    }

    public function down(): void
    {
        Schema::table('marketing_ads_data', function (Blueprint $table) {
            $table->dropColumn([
                'account_name',
                'program_name',
                'pic',
                'age',
                'gender',
                'landing_page_views',
                'add_to_cart',
            ]);
        });
    }
};
