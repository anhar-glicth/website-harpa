<?php

require __DIR__ . '/laravel/vendor/autoload.php';
$app = require_once __DIR__ . '/laravel/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\TodoList;

view()->share('errors', new \Illuminate\Support\ViewErrorBag);

try {
    $user = User::where('role', 'admin')->first();
    auth()->login($user);

    $allTodos = TodoList::with(['user', 'assigner'])->latest()->get();
    $todos = TodoList::with(['user', 'assigner'])->latest()->paginate(15);
    $workers = User::all();
    $myTodos = $allTodos;

    echo '1. Testing admin.tasks view... ';
    $html = view('admin.tasks', compact('todos', 'allTodos', 'workers', 'myTodos'))->render();
    echo 'SUCCESS (length: ' . strlen($html) . ')' . PHP_EOL;

    echo '2. Testing field.dashboard view... ';
    $fieldUser = User::where('role', 'field_worker')->first();
    auth()->login($fieldUser);
    $todayAttendance = collect();
    $target = null;
    $myCampaigns = collect();
    $myNews = collect();
    $myDistributions = collect();
    $stats = [
        'totalCampaigns' => 0,
        'totalNews' => 0,
        'totalFunds' => 0,
        'presentDays' => 0,
        'totalTasks' => 0,
        'completedTasks' => 0,
        'taskRate' => 0,
        'prospectCount' => 0,
        'followupCount' => 0,
        'closingCount' => 0,
    ];
    $allCampaigns = collect();
    $html2 = view('field.dashboard', compact(
        'todayAttendance', 'todos', 'target', 
        'myCampaigns', 'myNews', 'myDistributions', 'stats', 'allCampaigns'
    ))->render();
    echo 'SUCCESS (length: ' . strlen($html2) . ')' . PHP_EOL;

    echo '3. Testing dashboard view... ';
    $donaturUser = User::where('role', 'user')->first();
    auth()->login($donaturUser);
    $donations = \App\Models\Donation::paginate(5);
    $totalDonated = 0;
    $totalZakat = 0;
    $html3 = view('dashboard', [
        'donations' => $donations,
        'totalDonated' => $totalDonated,
        'totalZakat' => $totalZakat,
        'todayAttendance' => $todayAttendance,
        'todos' => $allTodos,
    ])->render();
    echo 'SUCCESS (length: ' . strlen($html3) . ')' . PHP_EOL;

} catch (Throwable $e) {
    echo 'ERROR: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine() . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}
