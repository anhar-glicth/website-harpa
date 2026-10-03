<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $commonPassword = Hash::make('password123');
        $rolesData = [
            ['name' => 'Admin Humanity', 'username' => 'admin', 'email' => 'admin@humanity.org', 'role' => 'admin'],
            ['name' => 'Super Admin Amal Abadi', 'username' => 'admin_abadi', 'email' => 'admin@abadi-ntb.com', 'role' => 'admin'],
            ['name' => 'Rina Staff Keuangan', 'username' => 'finance', 'email' => 'finance@humanity.org', 'role' => 'finance'],
            ['name' => 'Staff Keuangan Amal Abadi', 'username' => 'finance_abadi', 'email' => 'finance@abadi-ntb.com', 'role' => 'finance'],
            ['name' => 'Budi Petugas Lapangan', 'username' => 'petugas', 'email' => 'petugas@humanity.org', 'role' => 'field_worker'],
            ['name' => 'Petugas Lapangan Amal Abadi', 'username' => 'petugas_abadi', 'email' => 'petugas@abadi-ntb.com', 'role' => 'field_worker'],
            ['name' => 'Fajri Manager Program', 'username' => 'program', 'email' => 'program@humanity.org', 'role' => 'program_manager'],
            ['name' => 'Manager Program Amal Abadi', 'username' => 'program_abadi', 'email' => 'program@abadi-ntb.com', 'role' => 'program_manager'],
            ['name' => 'Dwi Staff CRM Telemarketing', 'username' => 'crm', 'email' => 'crm@humanity.org', 'role' => 'crm_staff'],
            ['name' => 'Staff CRM Amal Abadi', 'username' => 'crm_abadi', 'email' => 'crm@abadi-ntb.com', 'role' => 'crm_staff'],
            ['name' => 'Siti Admin Data Entry', 'username' => 'operator', 'email' => 'operator@humanity.org', 'role' => 'operator'],
            ['name' => 'Operator Amal Abadi', 'username' => 'operator_abadi', 'email' => 'operator@abadi-ntb.com', 'role' => 'operator'],
            ['name' => 'Ahmad Donatur', 'username' => 'donatur', 'email' => 'donatur@humanity.org', 'role' => 'user'],
            ['name' => 'Donatur Amal Abadi', 'username' => 'donatur_abadi', 'email' => 'donatur@abadi-ntb.com', 'role' => 'user'],
        ];

        foreach ($rolesData as $data) {
            try {
                $user = User::where('email', $data['email'])->first();
                if ($user) {
                    $user->update([
                        'name' => $data['name'],
                        'role' => $data['role'],
                        'password' => $commonPassword,
                    ]);
                } else {
                    $targetUsername = $data['username'];
                    if (User::where('username', $targetUsername)->exists()) {
                        $cleanDomain = str_replace(['@', '.', '-'], '_', explode('@', $data['email'])[1] ?? 'user');
                        $targetUsername = $data['username'] . '_' . $cleanDomain;
                        while (User::where('username', $targetUsername)->exists()) {
                            $targetUsername = $data['username'] . '_' . rand(100, 9999);
                        }
                    }
                    User::create([
                        'name' => $data['name'],
                        'username' => $targetUsername,
                        'email' => $data['email'],
                        'role' => $data['role'],
                        'password' => $commonPassword,
                    ]);
                }
            } catch (\Throwable $e) {
                // Ignore errors to allow migration to finish cleanly
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse
    }
};
