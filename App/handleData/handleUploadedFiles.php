<?php

declare(strict_types=1);

namespace App\handleData;

use App\exceptions\notValidFileException;

class handleUploadedFiles{
    private array $file;
    private string $uploadDir;

    public function __construct(array $file, string $uploadDir = './files/') {
        $this->file = $file;
        $this->uploadDir = $uploadDir;

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    public function validateFile() : bool{
        $allowedFileSize = 2000000;
        if (isset($this->file['tmp_name']) && is_file($this->file['tmp_name'])) {
            // Check file extension
            $allowedExtensions = ['pdf'];
            $fileExtension = pathinfo($this->file['name'], PATHINFO_EXTENSION);
            if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
                return false;
            }

            // Check MIME type
            // $allowedMimeTypes = ['application/pdf'];
            // $fileMimeType = mime_content_type($this->file['tmp_name']);
            // if (!in_array($fileMimeType, $allowedMimeTypes)) {
            //     return "File is not a PDF based on MIME type.";
            // }

            if (!$this->isPDF($this->file['tmp_name'])) {
                return false;
            }

            if($this->file['size'] >= $allowedFileSize){
                return false;
            }

            return true;
        } else {
            throw new notValidFileException();
        }
    }

    private function isPDF(string $filePath): bool {
        $fileHandle = fopen($filePath, 'rb');
        $header = fread($fileHandle, 4);
        fclose($fileHandle);

        return $header === '%PDF';
    }
}