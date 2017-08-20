<?php

return [
    'app_id' => env('ONESIGNAL_APP_ID', ''),
    'rest_api' => env('ONESIGNAL_REST_API_KEY', ''),
    'sub_domain' => env('ONESIGNAL_SUBDOMAIN', ''),
    'userclass' => env('ONESIGNAL_USER_CLASS', '\\App\\Models\\User'),
];
