<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\DonorDashboardController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'submitContact']);
Route::get('/news', [HomeController::class, 'news'])->name('news');
Route::get('/news/{id}', [HomeController::class, 'newsDetail'])->name('news.detail');
Route::get('/volunteer', [HomeController::class, 'volunteer'])->name('volunteer');
Route::post('/volunteer', [HomeController::class, 'submitVolunteer']);
Route::post('/newsletter', [HomeController::class, 'submitNewsletter'])->name('newsletter');

Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
Route::get('/campaigns/{id}', [CampaignController::class, 'show'])->name('campaigns.show');

// Authenticated Routes (Donors / General Users)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DonorDashboardController::class, 'dashboard'])->name('dashboard');
    
    // Zakat
    Route::get('/zakat', [DonorDashboardController::class, 'zakatIndex'])->name('zakat.index');
    Route::post('/zakat', [DonorDashboardController::class, 'submitZakat'])->name('zakat.submit');
    
    // Prayers
    Route::get('/prayers', [DonorDashboardController::class, 'prayers'])->name('prayers.index');
    Route::post('/prayers', [DonorDashboardController::class, 'submitPrayer'])->name('prayers.submit');
    Route::post('/prayers/{id}/aamiin', [DonorDashboardController::class, 'upvotePrayer'])->name('prayers.upvote');
    
    // Profile
    Route::get('/profile/custom', [DonorDashboardController::class, 'profile'])->name('profile.custom');
    Route::post('/profile/custom', [DonorDashboardController::class, 'updateProfile'])->name('profile.update');
    
    // Campaign Creation by users
    Route::get('/campaigns/create/new', [CampaignController::class, 'create'])->name('campaigns.create');
    Route::post('/campaigns/store', [CampaignController::class, 'store'])->name('campaigns.store');
    
    // Donations checkout
    Route::get('/donate/{campaign_id}', [DonationController::class, 'checkout'])->name('donations.checkout');
    Route::post('/donate/{campaign_id}/submit', [DonationController::class, 'submitCheckout'])->name('donations.submit');
    Route::get('/receipt/{donation_id}', [DonationController::class, 'receipt'])->name('donations.receipt');
    Route::post('/receipt/{donation_id}/upload', [DonationController::class, 'uploadProof'])->name('donations.upload');
});

// Admin Protected Routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/analytics', [AdminController::class, 'analytics'])->name('admin.analytics');
    Route::get('/admin/campaigns', [AdminController::class, 'campaigns'])->name('admin.campaigns');
    Route::post('/admin/campaigns/{id}/approve', [AdminController::class, 'approveCampaign'])->name('admin.campaigns.approve');
    Route::post('/admin/campaigns/{id}/reject', [AdminController::class, 'rejectCampaign'])->name('admin.campaigns.reject');
    Route::get('/admin/donations', [AdminController::class, 'donations'])->name('admin.donations');
    Route::post('/admin/donations/{id}/verify', [AdminController::class, 'verifyDonation'])->name('admin.donations.verify');
    Route::get('/admin/distributions', [AdminController::class, 'distributions'])->name('admin.distributions');
    Route::post('/admin/distributions/store', [AdminController::class, 'storeDistribution'])->name('admin.distributions.store');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/users/{id}/monitoring', [AdminController::class, 'userMonitoring'])->name('admin.users.monitoring');
    Route::post('/admin/users/{id}/tasks', [AdminController::class, 'assignTask'])->name('admin.users.assign_task');
    
    // Global Staff Monitoring Routes
    Route::get('/admin/attendance', [AdminController::class, 'attendanceList'])->name('admin.attendance');
    Route::get('/admin/tasks', [AdminController::class, 'taskList'])->name('admin.tasks');
    Route::post('/admin/tasks/store', [AdminController::class, 'storeTask'])->name('admin.tasks.store');
    Route::get('/admin/performance', [AdminController::class, 'performanceList'])->name('admin.performance');
    
    Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/admin/settings/update', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
});

// Field Worker Protected Routes
Route::middleware(['auth', 'field'])->group(function () {
    Route::get('/field/dashboard', [FieldController::class, 'dashboard'])->name('field.dashboard');
    Route::post('/field/attendance', [FieldController::class, 'attendanceStore'])->name('field.attendance.store');
    Route::post('/field/todo/store', [FieldController::class, 'todoStore'])->name('field.todo.store');
    Route::post('/field/todo/{id}/update', [FieldController::class, 'todoUpdate'])->name('field.todo.update');
    Route::get('/field/prospects', [FieldController::class, 'prospects'])->name('field.prospects');
    Route::post('/field/prospects/store', [FieldController::class, 'storeProspect'])->name('field.prospects.store');
    Route::post('/field/prospects/{id}/response', [FieldController::class, 'storeProspectResponse'])->name('field.prospects.response');
    
    // Additional staff features matching Vue
    Route::post('/field/campaigns/store', [FieldController::class, 'campaignStore'])->name('field.campaigns.store');
    Route::post('/field/campaigns/{id}/update', [FieldController::class, 'campaignUpdateStore'])->name('field.campaigns.update');
    Route::post('/field/news/store', [FieldController::class, 'newsStore'])->name('field.news.store');
    Route::post('/field/distributions/store', [FieldController::class, 'distributionStore'])->name('field.distributions.store');
});

require __DIR__.'/auth.php';
