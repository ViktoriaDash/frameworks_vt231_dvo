<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Реєстрація сервісів застосунку.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Ініціалізація сервісів: налаштування Gates для ролей та обмеження спроб логіну.
     *
     * @return void
     */
    public function boot(): void
    {
        Gate::define('client', fn (User $user) => $user->isClient());
        Gate::define('manager', fn (User $user) => $user->isManager());
        Gate::define('admin', fn (User $user) => $user->isAdmin());

        RateLimiter::for('login', fn (Request $request) => Limit::perMinutes(15, 5)
            ->by(mb_strtolower((string) $request->input('email')) . '|' . $request->ip()));
    }
}