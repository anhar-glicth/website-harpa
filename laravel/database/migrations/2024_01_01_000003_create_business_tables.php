<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('group_id')->nullable()->constrained('program_groups')->nullOnDelete();
            $table->string('title');
            $table->text('description');
            $table->string('category', 100)->nullable();
            $table->decimal('target_amount', 15, 2);
            $table->decimal('current_amount', 15, 2)->default(0);
            $table->longText('foto')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });

        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('payment_via_id')->nullable()->constrained('payment_via')->nullOnDelete();
            $table->string('donor_name');
            $table->decimal('amount', 15, 2);
            $table->text('message')->nullable();
            $table->string('payment_method', 100)->nullable();
            $table->enum('payment_status', ['Pending', 'Success', 'Failed'])->default('Pending');
            $table->enum('tracking_status', ['Diterima', 'Logistik', 'Dikirim', 'Tersalur'])->default('Diterima');
            $table->longText('proof_image')->nullable();
            $table->text('admin_note')->nullable();
            $table->timestamp('donation_date')->useCurrent();
            $table->timestamps();
        });

        Schema::create('distributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->nullable()->constrained('campaigns')->cascadeOnDelete();
            $table->foreignId('worker_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('location_name')->nullable();
            $table->longText('photo_url')->nullable();
            $table->text('maps_link')->nullable();
            $table->string('recipient_name')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->date('distribution_date')->nullable();
            $table->enum('status', ['Proses', 'Tersalur'])->default('Proses');
            $table->text('notes')->nullable();
            $table->string('asnaf', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('source_of_fund', 100)->nullable();
            $table->integer('pm_count')->default(1);
            $table->string('category_name', 100)->nullable();
            $table->timestamps();
        });

        Schema::create('prospects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });

        Schema::create('prospect_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prospect_id')->nullable()->constrained('prospects')->cascadeOnDelete();
            $table->text('response')->nullable();
            $table->enum('status', ['Tertarik', 'Tidak Tertarik', 'Follow Up'])->default('Follow Up');
            $table->timestamps();
        });

        Schema::create('fundraisers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->enum('type', ['Internal', 'Mitra', 'Volunteer'])->default('Internal');
            $table->decimal('target', 15, 2)->default(0);
            $table->enum('status', ['Aktif', 'Non-Aktif'])->default('Aktif');
            $table->timestamps();
        });

        Schema::create('volunteers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->text('skills')->nullable();
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });

        Schema::create('pic_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pic_id')->constrained('users')->cascadeOnDelete();
            $table->date('month_period');
            $table->decimal('target_amount', 15, 2);
            $table->timestamps();
        });

        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title')->nullable();
            $table->string('tag', 100)->nullable();
            $table->text('content')->nullable();
            $table->longText('image')->nullable();
            $table->timestamps();
        });

        Schema::create('campaign_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('content');
            $table->longText('image')->nullable();
            $table->timestamps();
        });

        Schema::create('prayers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name')->nullable();
            $table->text('message')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->integer('aamiin_count')->default(0);
            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('subject')->nullable();
            $table->text('message')->nullable();
            $table->enum('status', ['unread', 'read', 'replied'])->default('unread');
            $table->timestamps();
        });

        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('type', ['check_in', 'check_out', 'sakit', 'izin']);
            $table->string('location')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->longText('attachment')->nullable();
            $table->timestamps();
        });

        Schema::create('todo_list', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedBigInteger('assigned_by')->nullable();
            $table->foreign('assigned_by')->references('id')->on('users')->nullOnDelete();
            $table->string('task');
            $table->text('description')->nullable();
            $table->dateTime('deadline')->nullable();
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->longText('result_attachment')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('zakats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('zakat_type', 100);
            $table->decimal('amount', 15, 2);
            $table->enum('payment_status', ['Pending', 'Success', 'Failed'])->default('Pending');
            $table->text('admin_note')->nullable();
            $table->timestamps();
        });

        Schema::create('muallaf_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->date('desired_date')->nullable();
            $table->string('location')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->timestamps();
        });

        Schema::create('newsletters', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->enum('status', ['active', 'unsubscribed'])->default('active');
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action')->nullable();
            $table->string('module', 100)->nullable();
            $table->text('details')->nullable();
            $table->timestamps();
        });

        Schema::create('targets', function (Blueprint $table) {
            $table->id();
            $table->string('period', 50);
            $table->foreignId('program_id')->nullable()->constrained('campaigns')->nullOnDelete();
            $table->decimal('amount', 15, 2);
            $table->decimal('achievement', 15, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->text('hero_title')->nullable();
            $table->text('hero_subtitle')->nullable();
            $table->longText('hero_image')->nullable();
            $table->text('about_mission')->nullable();
            $table->longText('about_image')->nullable();
            $table->text('impact_title')->nullable();
            $table->longText('impact_image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('targets');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('newsletters');
        Schema::dropIfExists('muallaf_requests');
        Schema::dropIfExists('zakats');
        Schema::dropIfExists('todo_list');
        Schema::dropIfExists('attendance');
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('prayers');
        Schema::dropIfExists('campaign_updates');
        Schema::dropIfExists('news');
        Schema::dropIfExists('pic_targets');
        Schema::dropIfExists('volunteers');
        Schema::dropIfExists('fundraisers');
        Schema::dropIfExists('prospect_responses');
        Schema::dropIfExists('prospects');
        Schema::dropIfExists('distributions');
        Schema::dropIfExists('donations');
        Schema::dropIfExists('campaigns');
    }
};
