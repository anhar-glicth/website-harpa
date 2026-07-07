<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Master Data Tables
        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('phone', 20)->nullable();
            $table->enum('status', ['Aktif', 'Non-Aktif'])->default('Aktif');
            $table->timestamps();
        });

        Schema::create('donor_channels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 50)->unique()->nullable();
            $table->enum('status', ['Aktif', 'Non-Aktif'])->default('Aktif');
            $table->timestamps();
        });

        Schema::create('program_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 50)->unique()->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('payment_via', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('provider')->nullable();
            $table->string('account_no', 100)->nullable();
            $table->enum('status', ['Aktif', 'Non-Aktif'])->default('Aktif');
            $table->timestamps();
        });

        Schema::create('prospect_media', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('status', ['Aktif', 'Non-Aktif'])->default('Aktif');
            $table->timestamps();
        });

        Schema::create('prospect_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('prospect_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('priority', ['Tinggi', 'Sedang', 'Rendah'])->default('Sedang');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prospect_types');
        Schema::dropIfExists('prospect_methods');
        Schema::dropIfExists('prospect_media');
        Schema::dropIfExists('payment_via');
        Schema::dropIfExists('program_groups');
        Schema::dropIfExists('donor_channels');
        Schema::dropIfExists('offices');
    }
};
