<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('distributions', function (Blueprint $table) {
            if (DB::getDriverName() === 'mysql') {
                $foreignKeys = DB::select("
                    SELECT CONSTRAINT_NAME 
                    FROM information_schema.TABLE_CONSTRAINTS 
                    WHERE TABLE_SCHEMA = DATABASE() 
                      AND TABLE_NAME = 'distributions' 
                      AND CONSTRAINT_NAME = 'distributions_program_id_foreign'
                      AND CONSTRAINT_TYPE = 'FOREIGN KEY'
                ");

                if (!empty($foreignKeys)) {
                    $table->dropForeign('distributions_program_id_foreign');
                }
            } else {
                try {
                    $table->dropForeign(['program_id']);
                } catch (\Throwable $e) {
                    // Ignore driver-specific unsupported or missing constraint errors
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distributions', function (Blueprint $table) {
            if (DB::getDriverName() === 'mysql') {
                try {
                    $table->foreign('program_id')->references('id')->on('campaigns')->cascadeOnDelete();
                } catch (\Throwable $e) {
                    // Ignore if data violates old foreign key constraint
                }
            }
        });
    }
};
