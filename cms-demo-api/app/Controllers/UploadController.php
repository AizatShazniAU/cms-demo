<?php
namespace App\Controllers;
use App\Core\JsonStore;
use App\Core\Response;
class UploadController
{
    private array $allowedExtensions = ['pdf', 'csv', 'xlsx', 'txt'];
    public function __construct(private JsonStore $store, private string $uploadPath) {}
    public function upload(array $user): void
    {
        if (! isset($_FILES['document'])) { Response::error('Please upload a file using the document field.', 422); return; }
        $file = $_FILES['document'];
        if ($file['error'] !== UPLOAD_ERR_OK) { Response::error('Upload failed.', 422, ['code' => $file['error']]); return; }
        $maxBytes = (int) env('UPLOAD_MAX_BYTES', 2097152);
        if ($file['size'] > $maxBytes) { Response::error('File is too large. Maximum size is ' . $maxBytes . ' bytes.', 422); return; }
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (! in_array($extension, $this->allowedExtensions, true)) { Response::error('Unsupported file type.', 422, ['allowed' => $this->allowedExtensions]); return; }
        if (! is_dir($this->uploadPath)) { mkdir($this->uploadPath, 0777, true); }
        $storedName = uniqid('upload_', true) . '.' . $extension;
        $targetPath = $this->uploadPath . DIRECTORY_SEPARATOR . $storedName;
        move_uploaded_file($file['tmp_name'], $targetPath);
        $record = [
            'id' => uniqid('doc_', true),
            'uploaded_by' => $user['email'],
            'original_name' => $file['name'],
            'stored_name' => $storedName,
            'size_bytes' => $file['size'],
            'mime_type' => mime_content_type($targetPath) ?: 'application/octet-stream',
            'uploaded_at' => gmdate('c'),
        ];
        $this->store->append('uploads', $record);
        Response::success($record, 'File uploaded successfully.');
    }
}
