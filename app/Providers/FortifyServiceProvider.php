<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Actions\Fortify\UpdateEmployerProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Add this
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
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
            // Dynamic binding based on the authenticated guard
        $this->app->bind(UpdatesUserProfileInformation::class, function () {
            if (auth('employer')->check()) {
                return app(UpdateEmployerProfileInformation::class);
            }

            return app(UpdateUserProfileInformation::class);
        });

        // Ensure Fortify uses the correct update logic
        Fortify::updateUserProfileInformationUsing(UpdatesUserProfileInformation::class);

        // Other Fortify actions
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        // Custom authentication logic
        Fortify::authenticateUsing(function (Request $request) {
            if (Auth::guard('web')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
                return Auth::guard('web')->user();
            }

            if (Auth::guard('employer')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
                return Auth::guard('employer')->user();
            }

            return null;
        });

        // Rate Limiting for Login
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());
            return Limit::perMinute(5)->by($throttleKey);
        });
    }
}
