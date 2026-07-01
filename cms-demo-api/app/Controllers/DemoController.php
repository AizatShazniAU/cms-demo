<?php
namespace App\Controllers;
use App\Core\Response;
class DemoController
{
    public function health(): void
    {
        Response::success(['service' => 'cms-demo-api', 'status' => 'online', 'time' => gmdate('c')], 'API is healthy.');
    }
    public function status(array $user): void
    {
        if (($_GET['fail'] ?? '') === '1') { Response::error('Forced demo error for frontend error-state handling.', 500); return; }
        Response::success([
            'module' => 'Demo Status',
            'authenticated_as' => $user['email'],
            'features' => ['auth', 'api-call', 'upload', 'report', 'activity-log', 'cache'],
            'time' => gmdate('c'),
        ], 'Demo API call completed.');
    }
}
