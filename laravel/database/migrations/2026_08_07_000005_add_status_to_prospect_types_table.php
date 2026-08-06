<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('prospect_types', 'status')) {
            Schema::table('prospect_types', function (Blueprint $table) {
                $table->enum('status', ['Aktif', 'Non-Aktif'])->default('Aktif')->after('priority');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('prospect_types', 'status')) {
            Schema::table('prospect_types', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
