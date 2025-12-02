<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Concede todos los permisos al rol 'Administrador'
        // Esta función se ejecuta antes que cualquier otra política de autorización.
        Gate::before(function ($user, $ability) {
            // Asumiendo que usas spatie/laravel-permission y tu rol de admin se llama 'Administrador'
            return $user->hasRole('Administrador') ? true : null;
        });
    }
}
