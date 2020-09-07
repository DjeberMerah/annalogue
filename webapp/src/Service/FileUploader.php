<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $target;

    public function __construct($target)
    {
        $this->target = $target;
    }

    public function upload(UploadedFile $file, string $id)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $filename = $originalFilename . '-' . $id . '.' . $file->guessExtension();

        try {
            $file->move($this->target, $filename);
        } catch (FileException $exception) {
            return null;
        }

        return $filename;
    }
}
