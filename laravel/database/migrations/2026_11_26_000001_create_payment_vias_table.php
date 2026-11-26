<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('payment_vias')) {
            Schema::create('payment_vias', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('account_number', 100)->nullable();
                $table->string('account_name', 100)->nullable();
                $table->string('bank_name', 100)->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_vias');
    }
};
