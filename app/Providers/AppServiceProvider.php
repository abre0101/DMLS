<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;

// Add these two lines:
use Livewire\Livewire;
use App\Http\Livewire\DocumentSearch;

use App\View\Components\Dashboard\Stat;
use App\View\Components\Dashboard\Card;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Global view composer: Share notifications with all views
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $notifications = Auth::user()->unreadNotifications;
                $view->with('notifications', $notifications);
            }
        });

        // Register Livewire component explicitly
        Livewire::component('document-search', DocumentSearch::class);

        Blade::component('dashboard.card', Card::class);
        Blade::component('dashboard.stat', Stat::class);
    }

    public function register(): void
    {
        //
    }
}
