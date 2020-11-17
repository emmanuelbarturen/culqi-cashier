<?php namespace Emm\CulqiCashier;

use Illuminate\Support\ServiceProvider;

/**
 * Class CulqiCashierServiceProvider
 * @package Emm\CulqiCashier
 */
class CulqiCashierServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
        if (app()->isLocal()) {
            $this->loadViewsFrom(__DIR__ . '/views', 'culqi-cashier');
            $this->loadRoutesFrom(__DIR__ . '/routes.php');
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
