<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Offices
        $offices = [
            ['name' => 'Pusat Jakarta', 'address' => 'Jl. Sudirman No. 1, Jakarta Pusat', 'status' => 'Aktif'],
            ['name' => 'Cabang Bandung', 'address' => 'Jl. Asia Afrika No. 10, Bandung', 'status' => 'Aktif'],
            ['name' => 'Cabang Surabaya', 'address' => 'Jl. Tunjungan No. 5, Surabaya', 'status' => 'Aktif']
        ];
        foreach ($offices as $office) {
            \App\Models\Office::updateOrCreate(['name' => $office['name']], $office);
        }

        // 2. Program Groups
        $groups = [
            ['name' => 'Kemanusiaan', 'code' => 'KMN', 'description' => 'Program bantuan bencana dan sosial'],
            ['name' => 'Pendidikan', 'code' => 'PDD', 'description' => 'Program beasiswa dan sarana sekolah'],
            ['name' => 'Kesehatan', 'code' => 'KSH', 'description' => 'Program pengobatan dan sanitasi'],
            ['name' => 'Dakwah', 'code' => 'DKW', 'description' => 'Program syiar Islam dan pembangunan masjid']
        ];
        foreach ($groups as $group) {
            \App\Models\ProgramGroup::updateOrCreate(['code' => $group['code']], $group);
        }

        // 3. Donor Channels
        $channels = [
            ['name' => 'WEB', 'code' => 'WEB', 'status' => 'Aktif'],
            ['name' => 'Sosial Media', 'code' => 'SOSMED', 'status' => 'Aktif'],
            ['name' => 'Sekolah', 'code' => 'SKL', 'status' => 'Aktif'],
            ['name' => 'Retail', 'code' => 'RTL', 'status' => 'Aktif'],
            ['name' => 'Perusahaan', 'code' => 'PRSH', 'status' => 'Aktif'],
            ['name' => 'Galda', 'code' => 'GLD', 'status' => 'Aktif'],
            ['name' => 'Digital/Web', 'code' => 'DIGI', 'status' => 'Aktif'],
            ['name' => 'Celengan', 'code' => 'CLG', 'status' => 'Aktif'],
            ['name' => 'Cash', 'code' => 'CSH', 'status' => 'Aktif'],
            ['name' => 'Instagram', 'code' => 'IG', 'status' => 'Aktif'],
            ['name' => 'Facebook', 'code' => 'FB', 'status' => 'Aktif'],
            ['name' => 'WhatsApp', 'code' => 'WA', 'status' => 'Aktif'],
        ];
        foreach ($channels as $channel) {
            \App\Models\DonorChannel::updateOrCreate(['name' => $channel['name']], $channel);
        }

        // 4. Payment Via
        $payments = [
            ['name' => 'BSI-337', 'provider' => 'Bank Syariah Indonesia', 'account_no' => '712337337', 'status' => 'Aktif'],
            ['name' => 'Mandiri 001', 'provider' => 'Bank Mandiri', 'account_no' => '1370001001', 'status' => 'Aktif'],
            ['name' => 'Mandiri 737', 'provider' => 'Bank Mandiri', 'account_no' => '1370000737', 'status' => 'Aktif'],
            ['name' => 'Tripay', 'provider' => 'Payment Gateway', 'account_no' => 'TRIPAY-PG', 'status' => 'Aktif'],
            ['name' => 'Bank Permata', 'provider' => 'Bank Permata', 'account_no' => '00987654321', 'status' => 'Aktif'],
            ['name' => 'BMT', 'provider' => 'BMT Synergy', 'account_no' => '5544332211', 'status' => 'Aktif'],
            ['name' => 'Gopay', 'provider' => 'GoTo / E-Wallet', 'account_no' => '08123456789', 'status' => 'Aktif'],
            ['name' => 'Dana', 'provider' => 'Dana Indonesia', 'account_no' => '08123456788', 'status' => 'Aktif'],
            ['name' => 'ShopeePay', 'provider' => 'Shopee E-Wallet', 'account_no' => '08123456787', 'status' => 'Aktif'],
        ];
        foreach ($payments as $payment) {
            \App\Models\PaymentVia::updateOrCreate(['name' => $payment['name']], $payment);
        }

        // 5. Prospect Media, Methods, Types
        $prospectMediaItems = ['Visit', 'Crowdfunding', 'Ads', 'Email', 'WhatsApp'];
        foreach ($prospectMediaItems as $pmName) {
            \App\Models\ProspectMedia::firstOrCreate(['name' => $pmName], ['status' => 'Aktif']);
        }
        $prospectMethodItems = ['Presentasi', 'Blast / Broadcast', 'Prospek 2 Arah'];
        foreach ($prospectMethodItems as $pmMethodName) {
            \App\Models\ProspectMethod::firstOrCreate(['name' => $pmMethodName], ['status' => 'Aktif']);
        }
        $prospectTypeItems = [
            'LAPORAN',
            'KWITANSI',
            'FU 3',
            'FU 2',
            'FU 1',
            'Pengiriman Bukti Terima Donasi',
            'Follow up Non Prospek',
            'Penawaran UpSelling',
            'Loyalti Ucapan Milad'
        ];
        foreach ($prospectTypeItems as $ptName) {
            \App\Models\ProspectType::firstOrCreate(['name' => $ptName], ['status' => 'Aktif', 'priority' => 'Sedang']);
        }

        $fundraiserItems = ['Mitra', 'Volunteer', 'CRM', 'Crowdfunding', 'Booth', 'Digital Marketing'];
        foreach ($fundraiserItems as $fName) {
            \App\Models\Fundraiser::firstOrCreate(['name' => $fName], ['status' => 'Aktif']);
        }

        $masterResponseItems = [
            'Read',
            'Centang 2',
            'Centang 1',
            'Donasi / Closing',
            'Pertimbangan',
            'Nomor Tidak Aktif',
            'Tidak Berdonasi',
            'Promise To Donate',
            'Belum Respon'
        ];
        foreach ($masterResponseItems as $rName) {
            \App\Models\MasterProspectResponse::firstOrCreate(['name' => $rName], ['status' => 'Aktif']);
        }

        $sampleTargets = [
            ['target_code' => 'TGT-2026-08', 'target_date' => '2026-08-01', 'target_amount' => 50000000, 'notes' => 'Target Bulan Agustus 2026'],
            ['target_code' => 'TGT-2026-07', 'target_date' => '2026-07-01', 'target_amount' => 45000000, 'notes' => 'Target Bulan Juli 2026'],
            ['target_code' => 'TGT-2026-06', 'target_date' => '2026-06-01', 'target_amount' => 40000000, 'notes' => 'Target Bulan Juni 2026'],
        ];
        foreach ($sampleTargets as $st) {
            \App\Models\MasterTarget::updateOrCreate(['target_code' => $st['target_code']], $st);
        }

        $sampleDistributions = [
            [
                'distribution_code' => 'DIS-2026-001',
                'program_id'        => 1,
                'recipient_name'    => 'Ustadz Ahmad Syahid',
                'pic_name'          => 'Ustadz Ahmad Syahid',
                'pic_phone'         => '081234567890',
                'amount'            => 150000000,
                'distribution_date' => '2025-01-18',
                'city'              => 'Mataram',
                'province'          => 'Nusa Tenggara Barat',
                'asnaf'             => 'Fakir',
                'source_of_fund'    => 'Infak/Shodaqoh',
                'pm_count'          => 3600,
                'status'            => 'Tersalur',
                'notes'             => 'Penyaluran sembako & modal usaha warga fakir.'
            ],
            [
                'distribution_code' => 'DIS-2026-002',
                'program_id'        => 2,
                'recipient_name'    => 'Pengurus Panti Asuhan Al-Hidayah',
                'pic_name'          => 'H. Ridwan Hakim',
                'pic_phone'         => '085298765432',
                'amount'            => 55600000,
                'distribution_date' => '2025-02-15',
                'city'              => 'Ende',
                'province'          => 'Nusa Tenggara Timur',
                'asnaf'             => 'Miskin',
                'source_of_fund'    => 'Infak/Shodaqoh',
                'pm_count'          => 273,
                'status'            => 'Tersalur',
                'notes'             => 'Beasiswa pendidikan & renovasi panti.'
            ],
            [
                'distribution_code' => 'DIS-2026-003',
                'program_id'        => 1,
                'recipient_name'    => 'Masyarakat Bencana Banjir Sumbawa',
                'pic_name'          => 'Drs. Supriadi',
                'pic_phone'         => '087812345678',
                'amount'            => 169736000,
                'distribution_date' => '2025-03-20',
                'city'              => 'Sumbawa',
                'province'          => 'Nusa Tenggara Barat',
                'asnaf'             => 'Fisabilillah',
                'source_of_fund'    => 'Program',
                'pm_count'          => 4718,
                'status'            => 'Tersalur',
                'notes'             => 'Bantuan darurat logistik & obat-obatan.'
            ],
            [
                'distribution_code' => 'DIS-2026-004',
                'program_id'        => 2,
                'recipient_name'    => 'Muallaf Desa Pelosok Dompu',
                'pic_name'          => 'Ustadz Halim',
                'pic_phone'         => '081999888777',
                'amount'            => 13650000,
                'distribution_date' => '2025-04-10',
                'city'              => 'Dompu',
                'province'          => 'Nusa Tenggara Barat',
                'asnaf'             => 'Mualaf',
                'source_of_fund'    => 'Zakat',
                'pm_count'          => 150,
                'status'            => 'Tersalur',
                'notes'             => 'Pembinaan & bantuan keagamaan muallaf.'
            ],
            [
                'distribution_code' => 'DIS-2026-005',
                'program_id'        => 1,
                'recipient_name'    => 'Bantuan Pengobatan Medis Adik Aila',
                'pic_name'          => 'Siti Sarah',
                'pic_phone'         => '082111222333',
                'amount'            => 240996600,
                'distribution_date' => '2025-05-19',
                'city'              => 'Bima',
                'province'          => 'Nusa Tenggara Barat',
                'asnaf'             => 'Ibnusabil',
                'source_of_fund'    => 'Infak/Shodaqoh',
                'pm_count'          => 5036,
                'status'            => 'Tersalur',
                'notes'             => 'Pendampingan & penanganan gizi medis.'
            ],
            [
                'distribution_code' => 'DIS-2026-006',
                'program_id'        => 2,
                'recipient_name'    => 'Qurban Terbaik NTT 2025',
                'pic_name'          => 'Ahmad Fauzi',
                'pic_phone'         => '083888777666',
                'amount'            => 58853000,
                'distribution_date' => '2025-06-25',
                'city'              => 'Kupang',
                'province'          => 'Nusa Tenggara Timur',
                'asnaf'             => 'Non Asnaf',
                'source_of_fund'    => 'Wakaf',
                'pm_count'          => 867,
                'status'            => 'Tersalur',
                'notes'             => 'Penyaluran hewan qurban daerah 3T.'
            ],
            [
                'distribution_code' => 'DIS-2026-007',
                'program_id'        => 1,
                'recipient_name'    => 'Asesment Medis Dhuafa Bu Sri',
                'pic_name'          => 'Nurul Fatimah',
                'pic_phone'         => '081555666777',
                'amount'            => 165673836,
                'distribution_date' => '2025-07-27',
                'city'              => 'Lombok Barat',
                'province'          => 'Nusa Tenggara Barat',
                'asnaf'             => 'Fakir',
                'source_of_fund'    => 'Infak/Shodaqoh',
                'pm_count'          => 1450,
                'status'            => 'Tersalur',
                'notes'             => 'Bantuan biaya hidup & kesehatan lansia.'
            ],
            [
                'distribution_code' => 'DIS-2026-008',
                'program_id'        => 2,
                'recipient_name'    => 'Penyaluran Sedekah Qurban Terbaik NTB',
                'pic_name'          => 'Kharisma Utama',
                'pic_phone'         => '087777888999',
                'amount'            => 285000000,
                'distribution_date' => '2025-08-14',
                'city'              => 'Lombok Timur',
                'province'          => 'Nusa Tenggara Barat',
                'asnaf'             => 'Fakir',
                'source_of_fund'    => 'Infak/Shodaqoh',
                'pm_count'          => 5200,
                'status'            => 'Tersalur',
                'notes'             => 'Distribusi paket daging qurban siap saji.'
            ],
        ];
        foreach ($sampleDistributions as $sd) {
            \App\Models\Distribution::updateOrCreate(['distribution_code' => $sd['distribution_code']], $sd);
        }

        // 6. Users (Admin, Field Worker, User)
        $commonPassword = bcrypt('password123');

        $admin = \App\Models\User::updateOrCreate(
            ['email' => 'admin@humanity.org'],
            [
                'name' => 'Admin Humanity',
                'username' => 'admin',
                'password' => $commonPassword,
                'role' => 'admin',
                'office_id' => 1
            ]
        );

        $staff = \App\Models\User::updateOrCreate(
            ['email' => 'petugas@humanity.org'],
            [
                'name' => 'Budi Petugas Lapangan',
                'username' => 'petugas',
                'password' => $commonPassword,
                'role' => 'field',
                'office_id' => 1
            ]
        );

        $donor = \App\Models\User::updateOrCreate(
            ['email' => 'donatur@humanity.org'],
            [
                'name' => 'Ahmad Donatur',
                'username' => 'donatur',
                'password' => $commonPassword,
                'role' => 'user',
                'office_id' => 1
            ]
        );

        // 7. Campaigns
        $campaign = \App\Models\Campaign::create([
            'author_id' => $admin->id,
            'group_id' => 4, // Dakwah
            'title' => 'Wakaf Al-Quran Pelosok',
            'description' => 'Distribusi mushaf Al-Quran ke pesantren di wilayah terpencil.',
            'category' => 'Dakwah',
            'target_amount' => 25000000,
            'current_amount' => 500000,
            'status' => 'approved'
        ]);

        // 8. Donations
        \App\Models\Donation::create([
            'campaign_id' => $campaign->id,
            'user_id' => $donor->id,
            'payment_via_id' => 1,
            'donor_name' => 'Andi Donatur',
            'amount' => 500000,
            'payment_method' => 'Transfer Bank',
            'payment_status' => 'Success',
            'tracking_status' => 'Logistik',
            'donation_date' => now()
        ]);

        // 9. News
        \App\Models\News::create([
            'author_id' => $admin->id,
            'title' => 'Laporan Distribusi Tahap 1',
            'tag' => 'Laporan',
            'content' => 'Alhamdulillah, 500 mushaf telah sampai di NTT.'
        ]);

        // 10. Site Settings
        \App\Models\SiteSetting::create([
            'hero_title' => 'Mari Saling Peduli dan Berbagi',
            'hero_subtitle' => 'Membantu mereka yang membutuhkan di seluruh penjuru negeri',
            'about_mission' => 'Misi kami adalah menyalurkan donasi dengan aman dan transparan.',
            'impact_title' => 'Dampak Nyata Bersama Kami'
        ]);

        // 11. Fundraisers
        \App\Models\Fundraiser::create([
            'name' => 'Ahmad Fundraiser',
            'type' => 'Internal',
            'status' => 'Aktif'
        ]);

        // 12. Distributions with GPS Coordinates for Leaflet map
        \App\Models\Distribution::create([
            'program_id' => $campaign->id,
            'worker_id' => $staff->id,
            'title' => 'Distribusi Mushaf Al-Quran NTT',
            'description' => 'Penyaluran 200 mushaf Al-Quran ke Pondok Pesantren Al-Hidayah di Kupang, NTT.',
            'latitude' => -10.1772,
            'longitude' => 123.6070,
            'location_name' => 'Pesantren Al-Hidayah Kupang',
            'photo_url' => 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?q=80&w=600&fit=crop',
            'maps_link' => 'https://maps.google.com/?q=-10.1772,123.6070',
            'recipient_name' => 'Ponpes Al-Hidayah',
            'amount' => 5000000,
            'distribution_date' => now()->subDays(5),
            'status' => 'Tersalur'
        ]);

        \App\Models\Distribution::create([
            'program_id' => $campaign->id,
            'worker_id' => $staff->id,
            'title' => 'Bantuan Pangan Dhuafa Bandung',
            'description' => 'Penyaluran paket sembako untuk 50 keluarga prasejahtera di Cicadas, Bandung.',
            'latitude' => -6.9175,
            'longitude' => 107.6191,
            'location_name' => 'Cicadas, Bandung',
            'photo_url' => 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=600&fit=crop',
            'maps_link' => 'https://maps.google.com/?q=-6.9175,107.6191',
            'recipient_name' => 'Warga Cicadas',
            'amount' => 3000000,
            'distribution_date' => now()->subDays(2),
            'status' => 'Tersalur'
        ]);

        // 13. Karyawan Master Data
        $manager = \App\Models\Karyawan::updateOrCreate(
            ['karyawan_code' => 'KRY-001'],
            [
                'nama_lengkap' => 'Budi Santoso, S.E.',
                'jabatan' => 'Branch Manager',
                'no_hp' => '081234567890',
                'email' => 'budi.santoso@humanity.org',
                'alamat' => 'Jl. Sudirman No. 45, Jakarta Pusat',
                'status' => 'Aktif',
                'office_id' => 1,
                'parent_id' => null,
            ]
        );

        $supervisor = \App\Models\Karyawan::updateOrCreate(
            ['karyawan_code' => 'KRY-002'],
            [
                'nama_lengkap' => 'Siti Nurhaliza, M.M.',
                'jabatan' => 'Supervisor Lapangan',
                'no_hp' => '082198765432',
                'email' => 'siti.nurhaliza@humanity.org',
                'alamat' => 'Jl. Asia Afrika No. 88, Bandung',
                'status' => 'Aktif',
                'office_id' => 2,
                'parent_id' => $manager->id,
            ]
        );

        \App\Models\Karyawan::updateOrCreate(
            ['karyawan_code' => 'KRY-003'],
            [
                'nama_lengkap' => 'Ahmad Rifa\'i',
                'jabatan' => 'Staf Operasional',
                'no_hp' => '085712344321',
                'email' => 'ahmad.rifai@humanity.org',
                'alamat' => 'Jl. Pemuda No. 12, Surabaya',
                'status' => 'Aktif',
                'office_id' => 3,
                'parent_id' => $supervisor->id,
            ]
        );
    }
}
