<?php
namespace App\Controllers;
use App\Core\JsonStore;
use App\Core\Response;
class ActivityLogController
{
    public function __construct(private JsonStore $store) {}
    public function index(): void
    {
        Response::success(array_slice(array_reverse($this->store->read('activity-logs')), 0, 50), 'Recent activity logs retrieved.');
    }
}
