<?php

require __DIR__ . '/../laravel/vendor/autoload.php';
$app = require __DIR__ . '/../laravel/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

foreach (\App\Models\User::all() as $u) {
    echo "{$u->id} | {$u->username} | {$u->email} | {$u->name} | {$u->role}\n";
}
