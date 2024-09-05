<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
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
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('manage_kategori',function($user) {
            return $user->hasAnyPermission([
                'kategori_show',
                'kategori_create',
                'kategori_update',
                'kategori_detail',
                'kategori_edit',
                'kategori_delete'
            ]);
        });

        Gate::define('manage_users', function($user){
            return $user->hasAnyPermission([
                'users_show',
                'users_create',
                'users_update',
                'users_detail',
                'users_delete'
            ]);
        });

        Gate::define('manage_cerita', function($user){
            return $user->hasAnyPermission([
                'cerita_show',
                'cerita_create',
                'cerita_update',
                'cerita_detail',
                'cerita_delete'
            ]);
        });
    }
}
