<?php
namespace App\Controllers;
use App\Core\JsonStore;
use App\Core\Response;
class ReportController
{
    public function __construct(private JsonStore $store) {}
    public function index(): void
    {
        $rows = $this->filterRows();
        Response::success(array_values($rows), 'Report data retrieved.', [
            'filters' => ['year' => $_GET['year'] ?? null, 'category' => $_GET['category'] ?? null, 'status' => $_GET['status'] ?? null],
            'total' => count($rows),
        ]);
    }
    public function export(): void
    {
        $rows = $this->filterRows();
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="cms-demo-report-' . gmdate('Ymd-His') . '.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['Year', 'Category', 'Status', 'Region', 'Total']);
        foreach ($rows as $row) { fputcsv($out, [$row['year'], $row['category'], $row['status'], $row['region'], $row['total']]); }
        fclose($out);
    }
    private function filterRows(): array
    {
        $year = $_GET['year'] ?? '';
        $category = $_GET['category'] ?? '';
        $status = $_GET['status'] ?? '';
        return array_filter($this->store->read('report-records'), function (array $row) use ($year, $category, $status) {
            if ($year !== '' && (string) $row['year'] !== (string) $year) { return false; }
            if ($category !== '' && $row['category'] !== $category) { return false; }
            if ($status !== '' && $row['status'] !== $status) { return false; }
            return true;
        });
    }
}
