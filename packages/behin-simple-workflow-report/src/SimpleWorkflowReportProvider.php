<?php

namespace Behin\SimpleWorkflowReport;

use Illuminate\Support\ServiceProvider;

class SimpleWorkflowReportProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__. '/Migrations');
        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');
        $this->loadViewsFrom(__DIR__. '/Views', 'SimpleWorkflowReportView');
        $this->loadViewsFrom(__DIR__. '/Purchases/Views', 'PurchasesReportView');
        $this->loadTranslationsFrom(__DIR__ . '/Lang', 'SimpleWorkflowReportLang');
    }
}
