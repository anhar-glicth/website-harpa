<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('prospect_methods', 'status')) {
            Schema::table('prospect_methods', function (Blueprint $table) {
                $table->enum('status', ['Aktif', 'Non-Aktif'])->default('Aktif')->after('description');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('prospect_methods', 'status')) {
            Schema::table('prospect_methods', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
