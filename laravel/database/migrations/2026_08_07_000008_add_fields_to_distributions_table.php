<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('distributions', function (Blueprint $table) {
            if (!Schema::hasColumn('distributions', 'distribution_code')) {
                $table->string('distribution_code', 50)->nullable()->after('id');
            }
            if (!Schema::hasColumn('distributions', 'pic_name')) {
                $table->string('pic_name', 255)->nullable()->after('recipient_name');
            }
            if (!Schema::hasColumn('distributions', 'pic_phone')) {
                $table->string('pic_phone', 50)->nullable()->after('pic_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('distributions', function (Blueprint $table) {
            if (Schema::hasColumn('distributions', 'distribution_code')) {
                $table->dropColumn('distribution_code');
            }
            if (Schema::hasColumn('distributions', 'pic_name')) {
                $table->dropColumn('pic_name');
            }
            if (Schema::hasColumn('distributions', 'pic_phone')) {
                $table->dropColumn('pic_phone');
            }
        });
    }
};
