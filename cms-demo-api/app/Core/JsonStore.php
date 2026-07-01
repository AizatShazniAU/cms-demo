<?php
namespace App\Core;
class JsonStore
{
    public function __construct(private string $basePath)
    {
        if (! is_dir($this->basePath)) { mkdir($this->basePath, 0777, true); }
    }
    public function read(string $name, array $default = []): array
    {
        $path = $this->path($name);
        if (! file_exists($path)) { return $default; }
        $decoded = json_decode(file_get_contents($path) ?: '[]', true);
        return is_array($decoded) ? $decoded : $default;
    }
    public function write(string $name, array $data): void
    {
        file_put_contents($this->path($name), json_encode($data, JSON_PRETTY_PRINT));
    }
    public function append(string $name, array $row): void
    {
        $rows = $this->read($name);
        $rows[] = $row;
        $this->write($name, $rows);
    }
    public function path(string $name): string
    {
        return $this->basePath . DIRECTORY_SEPARATOR . $name . '.json';
    }
}
