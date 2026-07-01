<?php
namespace App\Controllers;
use App\Core\JsonStore;
use App\Core\Response;
class CacheDemoController
{
    public function __construct(private JsonStore $store, private string $cachePath) {}
    public function summary(): void
    {
        $cacheFile = $this->cachePath . DIRECTORY_SEPARATOR . 'summary.json';
        $ttl = (int) env('CACHE_TTL_SECONDS', 60);
        if (file_exists($cacheFile)) {
            $cached = json_decode(file_get_contents($cacheFile), true);
            if (is_array($cached) && time() - ($cached['created_unix'] ?? 0) < $ttl) { Response::success($cached['data'], 'Cached summary returned.', ['cached' => true]); return; }
        }
        $records = $this->store->read('report-records');
        $summary = ['total_records' => count($records), 'completed_records' => count(array_filter($records, fn ($row) => $row['status'] === 'Completed')), 'generated_at' => gmdate('c')];
        if (! is_dir($this->cachePath)) { mkdir($this->cachePath, 0777, true); }
        file_put_contents($cacheFile, json_encode(['created_unix' => time(), 'data' => $summary], JSON_PRETTY_PRINT));
        Response::success($summary, 'Fresh summary generated.', ['cached' => false]);
    }
    public function clear(): void
    {
        $cacheFile = $this->cachePath . DIRECTORY_SEPARATOR . 'summary.json';
        if (file_exists($cacheFile)) { unlink($cacheFile); }
        Response::success(['cleared' => true], 'Cache cleared.');
    }
}
