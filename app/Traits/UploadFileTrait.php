<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait UploadFileTrait
{
    public function uploadFile($file, $path, $name = null, $disk = 'public')
    {
        $extension = $file->getClientOriginalExtension();
        if (!$name) {
            $name = Str::uuid()->toString() . '.' . $extension;
        } else {
            $name = pathinfo($name, PATHINFO_FILENAME) . '.' . $extension;
        }

        Storage::disk($disk)->putFileAs($path, $file, $name);

        return $path . '/' . $name;
    }

    public function deleteFile($filePath, $disk = 'public')
    {
        return Storage::disk($disk)->delete($filePath);
    }

    public function getFileUrl($filePath, $disk = 'public')
    {
        return Storage::disk($disk)->url($filePath);
    }
}
