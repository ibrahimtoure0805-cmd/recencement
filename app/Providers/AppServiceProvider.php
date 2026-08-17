<?php

namespace App\Providers;

use App\Enums\RoleEnum;
use App\Models\Ressortissant;
use App\Policies\RessortissantPolicy;
use Illuminate\Support\Facades\Gate;
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
        Gate::before(function ($user, $ability) {
            return $user->hasRole(RoleEnum::SUPER_ADMIN) ? true : null;
        });

        Gate::policy(Ressortissant::class, RessortissantPolicy::class);
    }
}
