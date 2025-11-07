<?php

namespace Behin\Ami;

use Behin\Ami\Services\CallHistoryService;
use Illuminate\Support\ServiceProvider;

class AmiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/config/behin-ami.php', 'behin-ami');

        $this->app->singleton(CallHistoryService::class, function () {
            return new CallHistoryService();
        });
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/views', 'ami');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/config/behin-ami.php' => config_path('behin-ami.php'),
            ], 'behin-ami-config');
        }
    }
}
