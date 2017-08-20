# HRDNS Laravel OneSignal Package

## Setup
### config/app.php
```php
'providers' => [
    HRDNS\LaravelPackages\OneSignal\OneSignalProvider::class,
],
```

### .env
```ini
ONESIGNAL_APP_ID=<app_id>
ONESIGNAL_REST_API_KEY=<rest_api_key>
ONESIGNAL_SUBDOMAIN=[subdomain]
ONESIGNAL_USER_CLASS=<\App\User|\App\Models\User|...>
```

### Not fully HTTPS websites
If your website is not fully encrypted, you MUST use the `ONESIGNAL_SUBDOMAIN` system.

#### layout.blade.php
```html
<html>
  <body>
    [...]
    @include('onesignal::script')
  </body>
</html>
```

### Fully HTTPS websites
If your site is fully https encrypted. You MUST NOT use the `ONESIGNAL_SUBDOMAIN` system.
Please follow these steps.
 
#### layout.blade.php
```html
<html>
  <head>
    <link rel="manifest" href="/manifest.json">
  </head>
  <body>
    [...]
    @include('onesignal::script')
  </body>
</html>
```

#### Publish OneSignal JS Files and manifest.json
This command copy some needed files and configuration into the public folder.
```bash
php artisan vendor:publish --tag onesignal_public
```

## Usage
### Remove outdated OneSignalDevices
```bash
php artisan hrdns:onesignal:cleanup
```
This command downloads a csv file with all current tokens.
The csv file contains a field with the name `invalid_identifier`, this field is an boolean value:
- `t` => true
- `f` => false

### Broadcast Message
```php
onesignal()->createNotification([
    'included_segments' => ['All'],
    'contents' => ['en' => 'Content'],
    'headings'  => ['en' => 'Heading'],
    'url' => 'http://www.domain.tld',
]);
```

## Help
- https://documentation.onesignal.com/v3.0/reference
