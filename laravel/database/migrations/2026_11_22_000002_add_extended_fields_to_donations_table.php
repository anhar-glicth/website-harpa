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
        Schema::table('donations', function (Blueprint $table) {
            if (!Schema::hasColumn('donations', 'transaction_id')) {
                $table->string('transaction_id', 100)->nullable()->index()->after('id');
            }
            if (!Schema::hasColumn('donations', 'donor_phone')) {
                $table->string('donor_phone', 50)->nullable()->index()->after('donor_name');
            }
            if (!Schema::hasColumn('donations', 'donor_email')) {
                $table->string('donor_email', 255)->nullable()->after('donor_phone');
            }
            if (!Schema::hasColumn('donations', 'notes')) {
                $table->text('notes')->nullable()->after('message');
            }
            if (!Schema::hasColumn('donations', 'bank')) {
                $table->string('bank', 100)->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('donations', 'source')) {
                $table->string('source', 100)->nullable()->after('bank');
            }
            if (!Schema::hasColumn('donations', 'pic')) {
                $table->string('pic', 100)->nullable()->after('source');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $columnsToDrop = [];
            foreach (['transaction_id', 'donor_phone', 'donor_email', 'notes', 'bank', 'source', 'pic'] as $col) {
                if (Schema::hasColumn('donations', $col)) {
                    $columnsToDrop[] = $col;
                }
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
