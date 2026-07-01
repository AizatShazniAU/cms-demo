<?php
declare(strict_types=1);
$root = dirname(__DIR__);
$dataPath = $root . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'data';
foreach ([$dataPath, $root . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'uploads', $root . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'cache'] as $path) {
    if (! is_dir($path)) { mkdir($path, 0777, true); }
}
function writeJson(string $path, array $data): void { file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT)); }
writeJson($dataPath . DIRECTORY_SEPARATOR . 'users.json', [[
    'id' => 1,
    'name' => 'Demo Admin',
    'email' => 'demo@example.test',
    'role' => 'administrator',
    'password_hash' => password_hash('password', PASSWORD_DEFAULT),
]]);
writeJson($dataPath . DIRECTORY_SEPARATOR . 'tokens.json', []);
writeJson($dataPath . DIRECTORY_SEPARATOR . 'activity-logs.json', []);
writeJson($dataPath . DIRECTORY_SEPARATOR . 'uploads.json', []);
writeJson($dataPath . DIRECTORY_SEPARATOR . 'report-records.json', [
    ['id' => 1, 'year' => 2025, 'category' => 'Revenue', 'status' => 'Completed', 'region' => 'North', 'total' => 128000],
    ['id' => 2, 'year' => 2025, 'category' => 'Revenue', 'status' => 'Pending', 'region' => 'South', 'total' => 86000],
    ['id' => 3, 'year' => 2025, 'category' => 'Import', 'status' => 'Completed', 'region' => 'Central', 'total' => 42],
    ['id' => 4, 'year' => 2026, 'category' => 'Revenue', 'status' => 'Completed', 'region' => 'East', 'total' => 156000],
    ['id' => 5, 'year' => 2026, 'category' => 'Performance', 'status' => 'Completed', 'region' => 'North', 'total' => 91],
    ['id' => 6, 'year' => 2026, 'category' => 'Performance', 'status' => 'Pending', 'region' => 'South', 'total' => 17],
    ['id' => 7, 'year' => 2026, 'category' => 'Import', 'status' => 'Failed', 'region' => 'Central', 'total' => 3],
    ['id' => 8, 'year' => 2026, 'category' => 'Import', 'status' => 'Completed', 'region' => 'West', 'total' => 58],
]);
@unlink($root . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'summary.json');
echo "Seeded demo data. Login with demo@example.test / password" . PHP_EOL;
