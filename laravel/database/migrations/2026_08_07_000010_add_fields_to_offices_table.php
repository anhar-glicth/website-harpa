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
        Schema::table('offices', function (Blueprint $table) {
            if (!Schema::hasColumn('offices', 'office_code')) {
                $table->string('office_code', 50)->nullable()->after('id');
            }
            if (!Schema::hasColumn('offices', 'city')) {
                $table->string('city', 100)->nullable()->after('name');
            }
            if (!Schema::hasColumn('offices', 'province')) {
                $table->string('province', 100)->nullable()->after('city');
            }
            if (!Schema::hasColumn('offices', 'parent_id')) {
                $table->foreignId('parent_id')->nullable()->after('province')->constrained('offices')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offices', function (Blueprint $table) {
            if (Schema::hasColumn('offices', 'parent_id')) {
                $table->dropForeign(['parent_id']);
                $table->dropColumn('parent_id');
            }
            if (Schema::hasColumn('offices', 'province')) {
                $table->dropColumn('province');
            }
            if (Schema::hasColumn('offices', 'city')) {
                $table->dropColumn('city');
            }
            if (Schema::hasColumn('offices', 'office_code')) {
                $table->dropColumn('office_code');
            }
        });
    }
};
