<?php

namespace HRDNS\LaravelPackages\OneSignal\Models\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use HRDNS\LaravelPackages\OneSignal\Models\OneSignalDevice;

trait OneSignalUserTrait
{

    public static function bootOneSignalUserTrait()
    {
        //
    }

    /**
     * @return HasMany
     */
    public function oneSignalDevices()
    {
        return $this->hasMany(OneSignalDevice::class);
    }

}
