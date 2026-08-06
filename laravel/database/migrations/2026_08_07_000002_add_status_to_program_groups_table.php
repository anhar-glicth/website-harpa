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
        if (!Schema::hasColumn('program_groups', 'status')) {
            Schema::table('program_groups', function (Blueprint $table) {
                $table->enum('status', ['Aktif', 'Non-Aktif'])->default('Aktif')->after('description');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('program_groups', 'status')) {
            Schema::table('program_groups', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
