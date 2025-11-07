<?php

namespace UserNotifications\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class UserNotificationsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/config.php', 'user-notifications');

        $config = $this->app['config'];
        if (! $config->get('user-notifications.user_model')) {
            $config->set('user-notifications.user_model', config('auth.providers.users.model', \App\Models\User::class));
        }
    }

    public function boot(): void
    {
        if (!Gate::has('manage-user-notifications')) {
            Gate::define('manage-user-notifications', function ($user) {
                if (method_exists($user, 'access')) {
                    return (bool) $user->access('user-notifications.manage');
                }

                return true;
            });
        }

        $this->publishes([
            __DIR__ . '/../Config/config.php' => config_path('user-notifications.php'),
        ], 'user-notifications-config');

        $this->publishes([
            __DIR__ . '/../Resources/views' => resource_path('views/vendor/user-notifications'),
        ], 'user-notifications-views');

        $this->publishes([
            __DIR__ . '/../Database/Migrations' => database_path('migrations'),
        ], 'user-notifications-migrations');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'user-notifications');

        Blade::anonymousComponentPath(
            __DIR__ . '/../Resources/views/components',
            'user-notifications'
        );
    }
}
