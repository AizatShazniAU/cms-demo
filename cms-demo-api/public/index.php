<?php
declare(strict_types=1);

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (! str_starts_with($class, $prefix)) { return; }
    $relative = str_replace('\\', DIRECTORY_SEPARATOR, substr($class, strlen($prefix)));
    require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . $relative . '.php';
});

function env(string $key, mixed $default = null): mixed
{
    static $values = null;
    if ($values === null) {
        $values = [];
        $path = dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env';
        if (file_exists($path)) {
            foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                if (str_starts_with(trim($line), '#') || ! str_contains($line, '=')) { continue; }
                [$name, $value] = explode('=', $line, 2);
                $values[trim($name)] = trim($value, " \t\n\r\0\x0B\"");
            }
        }
    }
    return $values[$key] ?? $default;
}

use App\Controllers\ActivityLogController;
use App\Controllers\AuthController;
use App\Controllers\CacheDemoController;
use App\Controllers\DemoController;
use App\Controllers\ReportController;
use App\Controllers\UploadController;
use App\Core\Auth;
use App\Core\JsonStore;
use App\Core\Request;
use App\Core\Response;
use App\Middleware\ActivityLogger;

$frontendUrl = env('FRONTEND_URL', 'http://127.0.0.1:5173');
header('Access-Control-Allow-Origin: ' . $frontendUrl);
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

$store = new JsonStore(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'data');
$auth = new Auth($store);
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$method = $_SERVER['REQUEST_METHOD'];
$user = $auth->userFromBearer(Request::bearerToken());
$key = $method . ' ' . $path;

$publicRoutes = [
    'GET /api/health' => fn () => (new DemoController())->health(),
    'POST /api/login' => fn () => (new AuthController($auth))->login(),
];
if (isset($publicRoutes[$key])) { $publicRoutes[$key](); exit; }

if (! $user) { Response::error('Unauthenticated.', 401); exit; }
(new ActivityLogger($store))->log($user, $path, $method);

$protectedRoutes = [
    'GET /api/user' => fn () => (new AuthController($auth))->user($user),
    'GET /api/demo/status' => fn () => (new DemoController())->status($user),
    'POST /api/uploads' => fn () => (new UploadController($store, dirname(__DIR__) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'uploads'))->upload($user),
    'GET /api/reports' => fn () => (new ReportController($store))->index(),
    'GET /api/reports/export' => fn () => (new ReportController($store))->export(),
    'GET /api/activity-logs' => fn () => (new ActivityLogController($store))->index(),
    'GET /api/cache-demo/summary' => fn () => (new CacheDemoController($store, dirname(__DIR__) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'cache'))->summary(),
    'POST /api/cache-demo/clear' => fn () => (new CacheDemoController($store, dirname(__DIR__) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'cache'))->clear(),
];
if (! isset($protectedRoutes[$key])) { Response::error('Route not found.', 404); exit; }
$protectedRoutes[$key]();
