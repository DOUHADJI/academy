<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\User;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('is-admin', function (User $user) {
            return $user->role === "admin";
        });

        Gate::define('is-student', function (User $user) {
            return $user->role === "student";
        });

        Gate::define('is-professor', function(User $user)
        {
            return $user->role === "professor";
        });
    }
}