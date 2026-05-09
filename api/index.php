<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Maintenance Mode
|--------------------------------------------------------------------------
*/

if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
*/

require __DIR__ . '/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Create Writable Directories For Vercel
|--------------------------------------------------------------------------
*/

$directories = [
    __DIR__ . '/../storage',
    __DIR__ . '/../storage/app',
    __DIR__ . '/../storage/framework',
    __DIR__ . '/../storage/framework/cache',
    __DIR__ . '/../storage/framework/cache/data',
    __DIR__ . '/../storage/framework/sessions',
    __DIR__ . '/../storage/framework/views',
    __DIR__ . '/../storage/logs',
    __DIR__ . '/../bootstrap/cache',
];

foreach ($directories as $directory) {
    if (! is_dir($directory)) {
        @mkdir($directory, 0777, true);
    }
}

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
*/

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Maintenance Mode
|--------------------------------------------------------------------------
*/

if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
*/

require __DIR__ . '/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Create Writable Directories For Vercel
|--------------------------------------------------------------------------
*/

$directories = [
    __DIR__ . '/../storage',
    __DIR__ . '/../storage/app',
    __DIR__ . '/../storage/framework',
    __DIR__ . '/../storage/framework/cache',
    __DIR__ . '/../storage/framework/cache/data',
    __DIR__ . '/../storage/framework/sessions',
    __DIR__ . '/../storage/framework/views',
    __DIR__ . '/../storage/logs',
    __DIR__ . '/../bootstrap/cache',
];

foreach ($directories as $directory) {
    if (! is_dir($directory)) {
        @mkdir($directory, 0777, true);
    }
}

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
*/

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);