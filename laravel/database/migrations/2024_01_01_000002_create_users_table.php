<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop default users table and recreate with our schema
        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username', 100)->unique()->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'field_worker', 'user'])->default('user');
            $table->enum('gender', ['L', 'P', 'Other'])->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->longText('profile_photo')->nullable();
            $table->foreignId('office_id')->nullable()->constrained('offices')->nullOnDelete();
            $table->unsignedBigInteger('pic_prospect_id')->nullable();
            $table->foreignId('last_prospect_media_id')->nullable()->constrained('prospect_media')->nullOnDelete();
            $table->string('segment', 100)->nullable();
            $table->string('rfm_alltime', 100)->nullable();
            $table->date('prospect_date')->nullable();
            $table->text('source_info')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // Self-referencing FK
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('pic_prospect_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
