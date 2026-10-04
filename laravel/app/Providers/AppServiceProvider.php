<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer('layouts.app', function ($view) {
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('donations')) {
                    $realDonations = \App\Models\Donation::with('campaign')
                        ->where('payment_status', 'Success')
                        ->latest()
                        ->take(15)
                        ->get()
                        ->map(function ($d) {
                            return [
                                'name' => $d->donor_name ?: 'Hamba Allah',
                                'amount' => 'Rp ' . number_format($d->amount, 0, ',', '.'),
                                'campaign' => $d->campaign ? $d->campaign->title : 'Sedekah Peduli Sesama',
                                'time' => $d->created_at ? $d->created_at->diffForHumans() : 'Baru saja',
                                'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($d->donor_name ?: 'Hamba Allah') . '&background=f97316&color=fff&bold=true&size=64',
                            ];
                        });
                    $view->with('liveDonations', $realDonations);
                } else {
                    $view->with('liveDonations', collect());
                }
            } catch (\Throwable $e) {
                $view->with('liveDonations', collect());
            }
        });
    }
}
