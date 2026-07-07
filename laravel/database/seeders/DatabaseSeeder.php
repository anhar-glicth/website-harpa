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
            \App\Models\Office::create($office);
        }

        // 2. Program Groups
        $groups = [
            ['name' => 'Kemanusiaan', 'code' => 'KMN', 'description' => 'Program bantuan bencana dan sosial'],
            ['name' => 'Pendidikan', 'code' => 'PDD', 'description' => 'Program beasiswa dan sarana sekolah'],
            ['name' => 'Kesehatan', 'code' => 'KSH', 'description' => 'Program pengobatan dan sanitasi'],
            ['name' => 'Dakwah', 'code' => 'DKW', 'description' => 'Program syiar Islam dan pembangunan masjid']
        ];
        foreach ($groups as $group) {
            \App\Models\ProgramGroup::create($group);
        }

        // 3. Donor Channels
        $channels = [
            ['name' => 'Instagram', 'code' => 'IG', 'status' => 'Aktif'],
            ['name' => 'Facebook', 'code' => 'FB', 'status' => 'Aktif'],
            ['name' => 'WhatsApp', 'code' => 'WA', 'status' => 'Aktif'],
            ['name' => 'Website Direct', 'code' => 'WEB', 'status' => 'Aktif'],
            ['name' => 'Event Offline', 'code' => 'OFF', 'status' => 'Aktif']
        ];
        foreach ($channels as $channel) {
            \App\Models\DonorChannel::create($channel);
        }

        // 4. Payment Via
        $payments = [
            ['name' => 'Bank Mandiri', 'provider' => 'Transfer Bank', 'account_no' => '123-456-7890', 'status' => 'Aktif'],
            ['name' => 'Bank BCA', 'provider' => 'Transfer Bank', 'account_no' => '098-765-4321', 'status' => 'Aktif'],
            ['name' => 'GOPAY', 'provider' => 'E-Wallet', 'account_no' => '08123456789', 'status' => 'Aktif']
        ];
        foreach ($payments as $payment) {
            \App\Models\PaymentVia::create($payment);
        }

        // 5. Prospect Media, Methods, Types
        \App\Models\ProspectMedia::create(['name' => 'Brosur', 'status' => 'Aktif']);
        \App\Models\ProspectMedia::create(['name' => 'Online Ads', 'status' => 'Aktif']);
        \App\Models\ProspectMethod::create(['name' => 'Cold Call', 'description' => 'Telepon langsung']);
        \App\Models\ProspectType::create(['name' => 'Premium', 'priority' => 'Tinggi']);

        // 6. Users (Admin, Field Worker, User)
        $commonPassword = bcrypt('password123');

        $admin = User::create([
            'name' => 'Admin Humanity',
            'email' => 'admin@humanity.org',
            'username' => 'admin',
            'password' => $commonPassword,
            'role' => 'admin',
            'office_id' => 1
        ]);

        $staff = User::create([
            'name' => 'Petugas Lapangan',
            'email' => 'petugas@humanity.org',
            'username' => 'petugas',
            'password' => $commonPassword,
            'role' => 'field_worker',
            'office_id' => 1
        ]);

        $donor = User::create([
            'name' => 'Andi Donatur',
            'email' => 'donatur@humanity.org',
            'username' => 'donatur',
            'password' => $commonPassword,
            'role' => 'user',
            'office_id' => 1
        ]);

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
    }
}
