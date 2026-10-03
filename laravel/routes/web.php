<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\DonorDashboardController;
use App\Http\Controllers\ProfileController;
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
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/custom', [DonorDashboardController::class, 'profile'])->name('profile.custom');
    Route::post('/profile/custom', [DonorDashboardController::class, 'updateProfile'])->name('profile.custom.update');
    
    // Campaign Creation by users
    Route::get('/campaigns/create/new', [CampaignController::class, 'create'])->name('campaigns.create');
    Route::post('/campaigns/store', [CampaignController::class, 'store'])->name('campaigns.store');
    
    // Donations checkout
    Route::get('/donate/{campaign_id}', [DonationController::class, 'checkout'])->name('donations.checkout');
    Route::post('/donate/{campaign_id}/submit', [DonationController::class, 'submitCheckout'])->name('donations.submit');
    Route::get('/receipt/{donation_id}', [DonationController::class, 'receipt'])->name('donations.receipt');
    Route::post('/receipt/{donation_id}/upload', [DonationController::class, 'uploadProof'])->name('donations.upload');

    // Universal Personal Attendance & Todo List Routes (All Roles)
    Route::post('/attendance', [FieldController::class, 'attendanceStore'])->name('attendance.store');
    Route::post('/todo/store', [FieldController::class, 'todoStore'])->name('todo.store');
    Route::post('/todo/{id}/update', [FieldController::class, 'todoUpdate'])->name('todo.update');
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
    
    // Distribusi Management Routes
    Route::get('/admin/distribution-dashboard', [AdminController::class, 'distributionDashboard'])->name('admin.distribution_dashboard');
    Route::get('/admin/distributions', [AdminController::class, 'distributions'])->name('admin.distributions');
    Route::post('/admin/distributions/store', [AdminController::class, 'storeDistribution'])->name('admin.distributions.store');
    Route::put('/admin/distributions/{id}/update', [AdminController::class, 'updateDistribution'])->name('admin.distributions.update');
    Route::delete('/admin/distributions/{id}/delete', [AdminController::class, 'deleteDistribution'])->name('admin.distributions.delete');
    Route::get('/admin/distributions/export', [AdminController::class, 'exportDistributions'])->name('admin.distributions.export');
    Route::post('/admin/distributions/import', [AdminController::class, 'importDistributions'])->name('admin.distributions.import');
    // Master Kantor Routes
    Route::get('/admin/offices', [AdminController::class, 'officesList'])->name('admin.offices');
    Route::post('/admin/offices/store', [AdminController::class, 'storeOffice'])->name('admin.offices.store');
    Route::put('/admin/offices/{id}/update', [AdminController::class, 'updateOffice'])->name('admin.offices.update');
    Route::delete('/admin/offices/{id}/delete', [AdminController::class, 'deleteOffice'])->name('admin.offices.delete');
    Route::get('/admin/offices/export', [AdminController::class, 'exportOffices'])->name('admin.offices.export');
    
    // Master Channel Donasi Routes
    Route::get('/admin/donor-channels', [AdminController::class, 'donorChannelsList'])->name('admin.donor_channels');
    Route::post('/admin/donor-channels/store', [AdminController::class, 'storeDonorChannel'])->name('admin.donor_channels.store');
    Route::put('/admin/donor-channels/{id}/update', [AdminController::class, 'updateDonorChannel'])->name('admin.donor_channels.update');
    Route::delete('/admin/donor-channels/{id}/delete', [AdminController::class, 'deleteDonorChannel'])->name('admin.donor_channels.delete');

    // Master Rumpun Program Routes
    Route::get('/admin/program-groups', [AdminController::class, 'programGroupsList'])->name('admin.program_groups');
    Route::post('/admin/program-groups/store', [AdminController::class, 'storeProgramGroup'])->name('admin.program_groups.store');
    Route::put('/admin/program-groups/{id}/update', [AdminController::class, 'updateProgramGroup'])->name('admin.program_groups.update');
    Route::delete('/admin/program-groups/{id}/delete', [AdminController::class, 'deleteProgramGroup'])->name('admin.program_groups.delete');

    // Master Program Routes
    Route::get('/admin/programs', [AdminController::class, 'programList'])->name('admin.programs');
    Route::post('/admin/programs/store', [AdminController::class, 'storeProgram'])->name('admin.programs.store');
    Route::put('/admin/programs/{id}/update', [AdminController::class, 'updateProgram'])->name('admin.programs.update');
    Route::delete('/admin/programs/{id}/delete', [AdminController::class, 'deleteProgram'])->name('admin.programs.delete');

    // Master Via Bayar Routes
    Route::get('/admin/payment-via', [AdminController::class, 'paymentViaList'])->name('admin.payment_via');
    Route::post('/admin/payment-via/store', [AdminController::class, 'storePaymentVia'])->name('admin.payment_via.store');
    Route::put('/admin/payment-via/{id}/update', [AdminController::class, 'updatePaymentVia'])->name('admin.payment_via.update');
    Route::delete('/admin/payment-via/{id}/delete', [AdminController::class, 'deletePaymentVia'])->name('admin.payment_via.delete');

    // Master Media Prospek Routes
    Route::get('/admin/prospect-media', [AdminController::class, 'prospectMediaList'])->name('admin.prospect_media');
    Route::post('/admin/prospect-media/store', [AdminController::class, 'storeProspectMedia'])->name('admin.prospect_media.store');
    Route::put('/admin/prospect-media/{id}/update', [AdminController::class, 'updateProspectMedia'])->name('admin.prospect_media.update');
    Route::delete('/admin/prospect-media/{id}/delete', [AdminController::class, 'deleteProspectMedia'])->name('admin.prospect_media.delete');

    // Master Metode Prospek Routes
    Route::get('/admin/prospect-methods', [AdminController::class, 'prospectMethodsList'])->name('admin.prospect_methods');
    Route::post('/admin/prospect-methods/store', [AdminController::class, 'storeProspectMethod'])->name('admin.prospect_methods.store');
    Route::put('/admin/prospect-methods/{id}/update', [AdminController::class, 'updateProspectMethod'])->name('admin.prospect_methods.update');
    Route::delete('/admin/prospect-methods/{id}/delete', [AdminController::class, 'deleteProspectMethod'])->name('admin.prospect_methods.delete');

    // Master Jenis Prospek Routes
    Route::get('/admin/prospect-types', [AdminController::class, 'prospectTypesList'])->name('admin.prospect_types');
    Route::post('/admin/prospect-types/store', [AdminController::class, 'storeProspectType'])->name('admin.prospect_types.store');
    Route::put('/admin/prospect-types/{id}/update', [AdminController::class, 'updateProspectType'])->name('admin.prospect_types.update');
    Route::delete('/admin/prospect-types/{id}/delete', [AdminController::class, 'deleteProspectType'])->name('admin.prospect_types.delete');

    // Master Fundraiser Routes
    Route::get('/admin/fundraisers', [AdminController::class, 'fundraisersList'])->name('admin.fundraisers');
    Route::post('/admin/fundraisers/store', [AdminController::class, 'storeFundraiser'])->name('admin.fundraisers.store');
    Route::put('/admin/fundraisers/{id}/update', [AdminController::class, 'updateFundraiser'])->name('admin.fundraisers.update');
    Route::delete('/admin/fundraisers/{id}/delete', [AdminController::class, 'deleteFundraiser'])->name('admin.fundraisers.delete');

    // Master Respon Prospek Routes
    Route::get('/admin/prospect-responses', [AdminController::class, 'prospectResponsesList'])->name('admin.prospect_responses');
    Route::post('/admin/prospect-responses/store', [AdminController::class, 'storeProspectResponseMaster'])->name('admin.prospect_responses.store');
    Route::put('/admin/prospect-responses/{id}/update', [AdminController::class, 'updateProspectResponseMaster'])->name('admin.prospect_responses.update');
    Route::delete('/admin/prospect-responses/{id}/delete', [AdminController::class, 'deleteProspectResponseMaster'])->name('admin.prospect_responses.delete');

    // Master Target Routes
    Route::get('/admin/targets', [AdminController::class, 'targetsList'])->name('admin.targets');
    Route::post('/admin/targets/store', [AdminController::class, 'storeTarget'])->name('admin.targets.store');
    Route::put('/admin/targets/{id}/update', [AdminController::class, 'updateTarget'])->name('admin.targets.update');
    Route::delete('/admin/targets/{id}/delete', [AdminController::class, 'deleteTarget'])->name('admin.targets.delete');
    Route::get('/admin/targets/export', [AdminController::class, 'exportTargets'])->name('admin.targets.export');
    
    // Global Staff Monitoring Routes
    Route::get('/admin/attendance', [AdminController::class, 'attendanceList'])->name('admin.attendance');
    Route::get('/admin/attendance/export', [AdminController::class, 'exportAttendance'])->name('admin.attendance.export');
    Route::get('/admin/tasks', [AdminController::class, 'taskList'])->name('admin.tasks');
    Route::post('/admin/tasks/store', [AdminController::class, 'storeTask'])->name('admin.tasks.store');
    Route::get('/admin/tasks/export', [AdminController::class, 'exportTasks'])->name('admin.tasks.export');
    
    // Transaction Management Routes
    Route::get('/admin/donors-list', [AdminController::class, 'donorsList'])->name('admin.donors_list');
    Route::post('/admin/donors/store', [AdminController::class, 'storeDonor'])->name('admin.donors.store');
    Route::put('/admin/donors/{id}/update', [AdminController::class, 'updateDonor'])->name('admin.donors.update');
    Route::delete('/admin/donors/{id}/delete', [AdminController::class, 'deleteDonor'])->name('admin.donors.delete');
    Route::get('/admin/donors/export', [AdminController::class, 'exportDonors'])->name('admin.donors.export');
    Route::post('/admin/donors/import', [AdminController::class, 'importDonors'])->name('admin.donors.import');
    Route::get('/admin/donors/{id}/receipt', [AdminController::class, 'donorReceipt'])->name('admin.donors.receipt');

    Route::get('/admin/transactions-list', [AdminController::class, 'transactionsList'])->name('admin.transactions_list');
    Route::post('/admin/transactions/store', [AdminController::class, 'storeTransaction'])->name('admin.transactions.store');
    Route::put('/admin/transactions/{id}/update', [AdminController::class, 'updateTransaction'])->name('admin.transactions.update');
    Route::delete('/admin/transactions/{id}/delete', [AdminController::class, 'deleteTransaction'])->name('admin.transactions.delete');
    Route::get('/admin/transactions/export', [AdminController::class, 'exportTransactions'])->name('admin.transactions.export');
    Route::post('/admin/transactions/import', [AdminController::class, 'importTransactions'])->name('admin.transactions.import');
    Route::get('/admin/transactions/{id}/receipt', [AdminController::class, 'transactionReceipt'])->name('admin.transactions.receipt');
    Route::get('/admin/prospects-list', [AdminController::class, 'prospectsList'])->name('admin.prospects_list');
    Route::post('/admin/prospects/store', [AdminController::class, 'storeProspect'])->name('admin.prospects.store');
    Route::put('/admin/prospects/{id}/update', [AdminController::class, 'updateProspect'])->name('admin.prospects.update');
    Route::delete('/admin/prospects/{id}/delete', [AdminController::class, 'deleteProspect'])->name('admin.prospects.delete');
    Route::get('/admin/prospects/export', [AdminController::class, 'exportProspects'])->name('admin.prospects.export');
    Route::post('/admin/prospects/import', [AdminController::class, 'importProspects'])->name('admin.prospects.import');
    Route::get('/admin/potential-donors', [AdminController::class, 'potentialDonors'])->name('admin.potential_donors');
    Route::post('/admin/potential-donors/store', [AdminController::class, 'storePotentialDonor'])->name('admin.potential_donors.store');
    Route::put('/admin/potential-donors/{id}/update', [AdminController::class, 'updatePotentialDonor'])->name('admin.potential_donors.update');
    Route::delete('/admin/potential-donors/{id}/delete', [AdminController::class, 'deletePotentialDonor'])->name('admin.potential_donors.delete');
    Route::post('/admin/potential-donors/{id}/set-donor', [AdminController::class, 'setAsDonor'])->name('admin.potential_donors.set_donor');
    Route::get('/admin/potential-donors/export', [AdminController::class, 'exportPotentialDonors'])->name('admin.potential_donors.export');
    Route::post('/admin/potential-donors/import', [AdminController::class, 'importPotentialDonors'])->name('admin.potential_donors.import');
    Route::get('/admin/transactions-dashboard', [AdminController::class, 'transactionsDashboard'])->name('admin.transactions_dashboard');
    Route::get('/admin/donors-dashboard', [AdminController::class, 'donorsDashboard'])->name('admin.donors_dashboard');
    Route::get('/admin/prospects-dashboard', [AdminController::class, 'prospectsDashboard'])->name('admin.prospects_dashboard');
    Route::get('/admin/target-vs-achievement', [AdminController::class, 'targetVsAchievementDashboard'])->name('admin.target_vs_achievement');
    Route::get('/admin/rfm-dashboard', [AdminController::class, 'rfmDashboard'])->name('admin.rfm_dashboard');

    // Super Admin Strictly Protected Routes (Kelola Staff, Web Settings, Master Karyawan, Evaluasi Kinerja)
    Route::middleware(['superadmin'])->group(function () {
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::post('/admin/users/store', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::put('/admin/users/{id}/update', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/admin/users/{id}/delete', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
        Route::get('/admin/users/export', [AdminController::class, 'exportUsers'])->name('admin.users.export');
        Route::get('/admin/users/{id}/monitoring', [AdminController::class, 'userMonitoring'])->name('admin.users.monitoring');
        Route::post('/admin/users/{id}/tasks', [AdminController::class, 'assignTask'])->name('admin.users.assign_task');

        Route::get('/admin/karyawan', [AdminController::class, 'karyawanList'])->name('admin.karyawan');
        Route::post('/admin/karyawan/store', [AdminController::class, 'storeKaryawan'])->name('admin.karyawan.store');
        Route::put('/admin/karyawan/{id}/update', [AdminController::class, 'updateKaryawan'])->name('admin.karyawan.update');
        Route::delete('/admin/karyawan/{id}/delete', [AdminController::class, 'deleteKaryawan'])->name('admin.karyawan.delete');
        Route::get('/admin/karyawan/export', [AdminController::class, 'exportKaryawan'])->name('admin.karyawan.export');

        Route::get('/admin/performance', [AdminController::class, 'performanceList'])->name('admin.performance');

        Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');
        Route::post('/admin/settings/update', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
    });
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
    Route::post('/field/donations/store', [FieldController::class, 'storeManualDonation'])->name('field.donations.store');
});

require __DIR__.'/auth.php';
