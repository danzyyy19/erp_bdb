<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Spk;
use App\Models\Notification;
use App\Policies\SpkPolicy;
use App\Policies\NotificationPolicy;

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
        // Register policies
        Gate::policy(Spk::class, SpkPolicy::class);
        Gate::policy(Notification::class, NotificationPolicy::class);

        // View Composer for Sidebar Badges (Optimized with Cache)
        \Illuminate\Support\Facades\View::composer('layouts.sidebar', function ($view) {
            $user = auth()->user();
            if (!$user)
                return;

            $pendingSpkCount = 0;
            $pendingFpbCount = 0;
            $pendingItemCount = 0;

            if ($user->isOwner()) {
                // Cache counts for 5 seconds to prevent spamming DB on navigation
                $pendingSpkCount = \Illuminate\Support\Facades\Cache::remember('pending_spk_count', 5, function () {
                    return \App\Models\Spk::pending()->count();
                });

                $pendingFpbCount = \Illuminate\Support\Facades\Cache::remember('pending_fpb_count', 5, function () {
                    return \App\Models\Fpb::pending()->count();
                });

                $pendingItemCount = \Illuminate\Support\Facades\Cache::remember('pending_item_count', 5, function () {
                    return \App\Models\Product::pendingApproval()->count();
                });
            }

            $view->with(compact('pendingSpkCount', 'pendingFpbCount', 'pendingItemCount'));
        });
    }
}
