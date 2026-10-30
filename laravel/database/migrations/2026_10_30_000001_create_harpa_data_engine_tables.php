<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Master Donatur (Satu orang = satu donatur_id DNRxxxxxx)
        if (!Schema::hasTable('master_donatur')) {
            Schema::create('master_donatur', function (Blueprint $table) {
                $table->string('donatur_id', 30)->primary(); // e.g. DNR013114
                $table->string('no_hp', 50)->nullable()->index();
                $table->string('nama_lengkap', 255);
                $table->string('nama_panggilan', 100)->nullable();
                $table->string('panggilan', 50)->nullable(); // Bapak/Ibu/Kakak
                $table->string('jenis_kelamin', 20)->nullable(); // L / P
                $table->string('email', 255)->nullable();
                $table->string('kota', 100)->nullable();
                $table->string('provinsi', 100)->nullable();
                $table->string('profil_donatur', 100)->nullable();
                $table->date('tgl_registrasi')->nullable();
                $table->string('sumber_informasi', 100)->nullable();
                $table->string('channel_akuisisi', 100)->nullable();
                $table->string('pic_prospek', 100)->nullable();
                $table->string('kantor_layanan', 100)->nullable();
                $table->boolean('is_aktif')->default(true);
                $table->text('catatan')->nullable();
                $table->timestamp('tgl_input')->nullable();
                $table->timestamps();
            });
        }

        // 2. Master Nomor (Satu nomor HP = satu baris)
        if (!Schema::hasTable('master_nomor')) {
            Schema::create('master_nomor', function (Blueprint $table) {
                $table->string('no_hp', 50)->primary(); // Selalu teks format 628...
                $table->string('donatur_id', 30)->index();
                $table->string('status_nomor', 50)->default('aktif'); // kepemilikan orang (aktif/salah)
                $table->boolean('wa_aktif')->default(true); // hasil WBS Pro (1=aktif, 0=mati)
                $table->date('tgl_catat')->nullable();
                $table->text('catatan')->nullable();
                $table->timestamps();

                $table->foreign('donatur_id')->references('donatur_id')->on('master_donatur')->cascadeOnDelete();
            });
        }

        // 3. Referensi Alias Campaign (Pemetaan judul campaign ekspor ke master campaign)
        if (!Schema::hasTable('ref_campaign_alias')) {
            Schema::create('ref_campaign_alias', function (Blueprint $table) {
                $table->id();
                $table->string('teks_asli', 255)->unique(); // Ejaan asli di file ekspor
                $table->foreignId('campaign_id')->nullable()->constrained('campaigns')->nullOnDelete();
                $table->string('nama_campaign_standar', 255)->nullable();
                $table->unsignedBigInteger('program_id')->nullable();
                $table->text('catatan')->nullable();
                $table->timestamps();
            });
        }

        // 4. Transaksi Donasi (FH-xxxxxx, IM-xxxxxx, WA-xxxxxx)
        if (!Schema::hasTable('transaksi_donasi')) {
            Schema::create('transaksi_donasi', function (Blueprint $table) {
                $table->string('donasi_id', 50)->primary(); // FH-000001, IM-000001, WA-000001
                $table->string('donatur_id', 30)->index();
                $table->string('no_hp_input', 50)->nullable()->index();
                $table->foreignId('campaign_id')->nullable()->constrained('campaigns')->nullOnDelete();
                $table->string('campaign_title_raw', 255)->nullable();
                $table->date('tanggal')->index();
                $table->time('jam')->nullable();
                $table->decimal('nominal', 15, 2)->default(0);
                $table->integer('kode_unik')->default(0);
                $table->decimal('total', 15, 2)->default(0);
                $table->string('via_bayar', 100)->nullable();
                $table->string('bank_id', 50)->nullable();
                $table->string('status_bayar', 50)->default('success')->index(); // success / waiting
                $table->string('channel_donasi', 50)->nullable(); // web_fh, web_im, wa
                $table->string('channel_konversi', 50)->nullable(); // iklan, organik, everpro
                $table->string('pic_transaksi', 100)->nullable();
                $table->string('utm_source', 100)->nullable();
                $table->string('utm_campaign', 100)->nullable();
                $table->string('sumber_data', 50)->nullable()->index(); // FH, IM, WA
                $table->string('ref_asal', 100)->nullable()->index(); // Invoice ID / Transaksi ID
                $table->string('batch_tarik', 100)->nullable()->index();
                $table->timestamp('tgl_input')->nullable();
                $table->timestamps();

                $table->foreign('donatur_id')->references('donatur_id')->on('master_donatur')->cascadeOnDelete();
            });
        }

        // 5. Raw FH & IM (Menyimpan baris ekspor web apa adanya)
        if (!Schema::hasTable('raw_fh_im')) {
            Schema::create('raw_fh_im', function (Blueprint $table) {
                $table->id();
                $table->string('sumber', 20)->index(); // FH atau IM
                $table->string('invoice_id', 100)->index();
                $table->longText('data_json');
                $table->string('batch_tarik', 100)->index();
                $table->timestamp('tgl_tempel')->nullable();
                $table->timestamps();

                $table->unique(['sumber', 'invoice_id']);
            });
        }

        // 6. Raw WA (Menyimpan catatan CRM mingguan)
        if (!Schema::hasTable('raw_wa')) {
            Schema::create('raw_wa', function (Blueprint $table) {
                $table->id();
                $table->string('transaksi_id', 100)->unique();
                $table->longText('data_json');
                $table->string('batch_tarik', 100)->index();
                $table->timestamp('tgl_tempel')->nullable();
                $table->timestamps();
            });
        }

        // 7. Raw Blast (Riwayat pengiriman pesan Everpro)
        if (!Schema::hasTable('raw_blast')) {
            Schema::create('raw_blast', function (Blueprint $table) {
                $table->id();
                $table->string('receiver_number', 50)->index();
                $table->string('broadcast_id', 100)->index();
                $table->string('message_id', 100)->nullable();
                $table->string('status', 50)->index(); // READ, DELIVERED, SUCCESS, FAILED, LAINNYA
                $table->timestamp('timestamp_blast')->nullable();
                $table->string('batch_tarik', 100)->index(); // perkenalan-YYYY-MM-DD, jumat-YYYY-MM-DD, payday-YYYY-MM-DD
                $table->longText('raw_json')->nullable();
                $table->timestamps();

                $table->unique(['receiver_number', 'broadcast_id']);
            });
        }

        // 8. Raw Cek WA / Daftar Nomor Mati (Hasil WBS Pro)
        if (!Schema::hasTable('raw_cek_wa')) {
            Schema::create('raw_cek_wa', function (Blueprint $table) {
                $table->id();
                $table->string('no_hp', 50)->index();
                $table->boolean('is_wa_aktif')->default(false); // Selalu 0 untuk nomor mati
                $table->date('tgl_cek')->index();
                $table->text('catatan')->nullable();
                $table->string('batch_tarik', 100)->nullable()->index();
                $table->timestamps();
            });
        }

        // 9. Kontrol Tarik Data Donasi (Log tarikan ekspor FH, IM, WA)
        if (!Schema::hasTable('kontrol_tarik')) {
            Schema::create('kontrol_tarik', function (Blueprint $table) {
                $table->id();
                $table->string('sumber', 50)->index(); // FH, IM, WA
                $table->string('periode', 50)->index(); // e.g. 2026-09
                $table->dateTime('tgl_tarik');
                $table->integer('jml_baris')->default(0);
                $table->string('id_pertama', 100)->nullable();
                $table->string('id_terakhir', 100)->nullable();
                $table->string('ditarik_oleh', 100)->nullable();
                $table->text('catatan')->nullable();
                $table->timestamps();

                $table->unique(['sumber', 'periode']);
            });
        }

        // 10. Kontrol Blast Everpro
        if (!Schema::hasTable('kontrol_blast')) {
            Schema::create('kontrol_blast', function (Blueprint $table) {
                $table->id();
                $table->date('tgl_blast')->index();
                $table->dateTime('tgl_tarik');
                $table->string('batch_label', 100);
                $table->integer('jml_baris_export')->default(0);
                $table->integer('jml_nomor_unik')->default(0);
                $table->string('ditarik_oleh', 100)->nullable();
                $table->text('catatan')->nullable();
                $table->timestamps();
            });
        }

        // 11. Referensi Perkenalan Terkirim (Siapa yang sudah dikenalkan)
        if (!Schema::hasTable('ref_perkenalan_terkirim')) {
            Schema::create('ref_perkenalan_terkirim', function (Blueprint $table) {
                $table->id();
                $table->string('no_hp', 50)->unique();
                $table->date('tgl_dikirim')->index();
                $table->string('broadcast_id', 100)->nullable();
                $table->text('catatan')->nullable();
                $table->timestamps();
            });
        }

        // 12. Harpa System Settings (Ambang batas sistem yang dapat diatur dinamis)
        if (!Schema::hasTable('harpa_settings')) {
            Schema::create('harpa_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key', 100)->unique();
                $table->text('value')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('harpa_settings');
        Schema::dropIfExists('ref_perkenalan_terkirim');
        Schema::dropIfExists('kontrol_blast');
        Schema::dropIfExists('kontrol_tarik');
        Schema::dropIfExists('raw_cek_wa');
        Schema::dropIfExists('raw_blast');
        Schema::dropIfExists('raw_wa');
        Schema::dropIfExists('raw_fh_im');
        Schema::dropIfExists('transaksi_donasi');
        Schema::dropIfExists('ref_campaign_alias');
        Schema::dropIfExists('master_nomor');
        Schema::dropIfExists('master_donatur');
    }
};

