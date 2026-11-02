<?php

require __DIR__ . '/../laravel/vendor/autoload.php';
$app = require __DIR__ . '/../laravel/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

$mapping = [
    'admin@abadi-ntb.com' => ['email' => 'admin@harpa.id', 'username' => 'admin_harpa', 'name' => 'Super Admin Harpa'],
    'finance@abadi-ntb.com' => ['email' => 'finance@harpa.id', 'username' => 'finance_harpa', 'name' => 'Staff Keuangan Harpa'],
    'petugas@abadi-ntb.com' => ['email' => 'petugas@harpa.id', 'username' => 'petugas_harpa', 'name' => 'Petugas Lapangan Harpa'],
    'program@abadi-ntb.com' => ['email' => 'program@harpa.id', 'username' => 'program_harpa', 'name' => 'Manager Program Harpa'],
    'crm@abadi-ntb.com' => ['email' => 'crm@harpa.id', 'username' => 'crm_harpa', 'name' => 'Staff CRM Harpa'],
    'operator@abadi-ntb.com' => ['email' => 'operator@harpa.id', 'username' => 'operator_harpa', 'name' => 'Operator Data Entry Harpa'],
    'donatur@abadi-ntb.com' => ['email' => 'donatur@harpa.id', 'username' => 'donatur_harpa', 'name' => 'Donatur Harpa'],
];

foreach ($mapping as $oldEmail => $newVals) {
    $user = User::where('email', $oldEmail)->first();
    if ($user) {
        $user->update([
            'email' => $newVals['email'],
            'username' => $newVals['username'],
            'name' => $newVals['name'],
        ]);
        echo "Updated {$oldEmail} -> {$newVals['email']}\n";
    }
}

// Ensure marketing_harpa exists
$m = User::where('email', 'marketing@harpa.id')->first();
if (!$m) {
    User::create([
        'name' => 'Staff Marketing Harpa',
        'username' => 'marketing_harpa',
        'email' => 'marketing@harpa.id',
        'role' => 'marketing',
        'password' => bcrypt('password123'),
    ]);
    echo "Created marketing@harpa.id\n";
}

echo "Database updated successfully!\n";
