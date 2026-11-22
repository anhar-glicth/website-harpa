<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

auth()->login(\App\Models\User::first());
view()->share('errors', new \Illuminate\Support\ViewErrorBag());

$req = \Illuminate\Http\Request::create('/admin/marketing/dashboard', 'GET');
$ctrl = app(\App\Http\Controllers\MarketingController::class);
$resp = $ctrl->dashboard($req);
$rendered = $resp->render();

echo "SUCCESS! Rendered marketing dashboard, length: " . strlen($rendered) . " bytes\n";
