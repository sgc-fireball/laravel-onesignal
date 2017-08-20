<?php

namespace HRDNS\LaravelPackages\OneSignal\Models;

use Illuminate\Database\Eloquent\Model;

class OneSignalDevice extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'one_signal_id',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(config('onesignal.userclass'));
    }

}
