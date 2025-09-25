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

    public function deleteFileByUrl($url, $disk = 'public')
    {
        $relativePath = $this->urlToPath($url, $disk);
        if ($relativePath) {
            return $this->deleteFile($relativePath, $disk);
        }
    }

    public function getFileUrl($filePath, $disk = 'public')
    {
        return Storage::disk($disk)->url($filePath);
    }

    public function moveFile($oldPath, $newPath, $disk = 'public')
    {
        if (Storage::disk($disk)->exists($oldPath)) {
            Storage::disk($disk)->move($oldPath, $newPath);
            return $newPath;
        }

        return null;
    }

    public function moveContentImages($content, $targetDir = 'uploads/editor', $disk = 'public')
    {
        preg_match_all('/<img[^>]+src="([^">]+)"/i', $content, $matches);
        $imageUrls = $matches[1] ?? [];

        foreach ($imageUrls as $url) {
            $publicUrl = Storage::disk($disk)->url('');
            $relativePath = $url;

            if (str_starts_with($url, $publicUrl)) {
                $relativePath = str_replace($publicUrl, '', $url);
            } elseif (str_starts_with($url, '../storage/')) {
                $relativePath = str_replace('../storage/', '', $url);
            }

            if (str_starts_with($relativePath, 'uploads/tmp')) {
                $filename = basename($relativePath);
                $newPath = $targetDir . '/' . $filename;

                if ($this->moveFile($relativePath, $newPath, $disk)) {
                    $content = str_replace($url, $this->getFileUrl($newPath, $disk), $content);
                }
            }
        }

        return $content;
    }

    public function extractImagesFromHtml($html): array
    {
        preg_match_all('/<img[^>]+src="([^">]+)"/i', $html, $matches);
        return $matches[1] ?? [];
    }

    public function urlToPath($url, $disk = 'public')
    {
        if (!$url) return null;

        $publicUrl = Storage::disk($disk)->url('');

        if (str_starts_with($url, $publicUrl)) {
            return str_replace($publicUrl, '', $url);
        } elseif (str_starts_with($url, '../storage/')) {
            return str_replace('../storage/', '', $url);
        }

        return $url;
    }

    public function collectFiles(...$fields): array
    {
        $files = [];

        foreach ($fields as $field) {
            if (!$field) continue;

            if (str_contains($field, '<img')) {
                $files = array_merge($files, $this->extractImagesFromHtml($field));
            } else {
                $files[] = $field;
            }
        }

        return array_values(array_unique($files));
    }
}
