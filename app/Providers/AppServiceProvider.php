<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\User;
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
        // Implicitly grant "Super Admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::before(function (User $user, string $ability, array $resources = []) {
            foreach ($resources as $resource) {
                switch (true) {
                    case $resource instanceof Role:
                        // disallow to update/delete Super Admin Role
                        if (in_array($ability, ['update', 'delete']) && $resource->isSuperAdmin()) {
                            return false;
                        }
                        break;
                    case $resource instanceof User:
                        // disallow to delete themself
                        if (in_array($ability, ['delete']) && $resource->is($user)) {
                            return false;
                        }
                        break;
                }
            }

            if ($user->isSuperAdmin()) {
                return true;
            }

            return null;
        });
    }
}
