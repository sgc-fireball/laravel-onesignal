<?php

namespace HRDNS\LaravelPackages\OneSignal;

use Route;
use HRDNS\LaravelPackages\OneSignal\Services\OneSignal;
use HRDNS\LaravelPackages\OneSignal\Services\OneSignalInterface;
use Illuminate\Support\ServiceProvider;

class OneSignalProvider extends ServiceProvider
{

    public function register()
    {
        require_once(__DIR__.'/Supports/helpers.php');
        $this->app->alias(OneSignalInterface::class, OneSignal::class);
        $this->mergeConfigFrom(__DIR__.'/Config/onesignal.php', 'onesignal');
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/Resources/Views', 'onesignal');
        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');

        $this->publishes([__DIR__.'/Resources/Views' => resource_path('views/vendor/onesignal')], 'onesignal_views');
        $this->publishes([__DIR__.'/Config/onesignal.php' => config_path('onesignal.php')], 'onesignal_config');
        $this->publishes([__DIR__.'/Resources/Public/OneSignalSDKUpdaterWorker.js' => public_path('OneSignalSDKUpdaterWorker.js')], 'onesignal_public');
        $this->publishes([__DIR__.'/Resources/Public/OneSignalSDKWorker.js' => public_path('OneSignalSDKWorker.js')], 'onesignal_public');
        $this->publishes([__DIR__.'/Resources/Public/OneSignalSDK.js' => public_path('OneSignalSDK.js')], 'onesignal_public');
        $this->publishes([__DIR__.'/Resources/Public/manifest.json' => public_path('manifest.json')], 'onesignal_public');

        $this->app->singleton(
            OneSignalInterface::class,
            function () {
                return (new OneSignal())->setConfig(config('onesignal'));
            }
        );

        if ($this->app->runningInConsole()) {
            $this->commands([Console\Commands\OneSignalCleanupCommand::class]);
            $this->commands([Console\Commands\OneSignalSendCommand::class]);
        }

        Route::middleware('web')
            ->namespace('HRDNS\LaravelPackages\OneSignal\Http\Controllers')
            ->group(__DIR__.'/Routes/web.php');
    }

}
