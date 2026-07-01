<?php
namespace App\Core;
class Response
{
    public static function json(array $payload, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($payload, JSON_PRETTY_PRINT);
    }
    public static function success(mixed $data, string $message = 'OK', array $meta = []): void
    {
        self::json(array_merge(['success' => true, 'message' => $message, 'data' => $data], $meta));
    }
    public static function error(string $message, int $status = 400, mixed $errors = null): void
    {
        $payload = ['success' => false, 'message' => $message];
        if ($errors !== null) { $payload['errors'] = $errors; }
        self::json($payload, $status);
    }
}
