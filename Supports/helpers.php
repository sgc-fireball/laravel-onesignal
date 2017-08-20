<?php

use HRDNS\LaravelPackages\OneSignal\Services\OneSignalInterface;

if (!function_exists('onesignal')) {
    function onesignal(): OneSignalInterface
    {
        return app(OneSignalInterface::class);
    }
}
