<?php
namespace App\Middleware;
use App\Core\JsonStore;
class ActivityLogger
{
    public function __construct(private JsonStore $store) {}
    public function log(?array $user, string $path, string $method): void
    {
        if (! $user || $path === '/api/activity-logs') { return; }
        $this->store->append('activity-logs', [
            'id' => uniqid('log_', true),
            'user_id' => $user['id'],
            'email' => $user['email'],
            'endpoint' => $path,
            'method' => $method,
            'timestamp' => gmdate('c'),
        ]);
    }
}
