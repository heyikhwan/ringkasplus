<?php

namespace App\Http\Controllers;

use App\Traits\UploadFileTrait;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    use UploadFileTrait;

    public function uploadEditor(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
        ]);

        if ($request->hasFile('file')) {
            $path = 'uploads/tmp';
            $savedPath = $this->uploadFile($request->file('file'), $path);

            return response()->json([
                'location' => $this->getFileUrl($savedPath)
            ]);
        }

        return response()->json(['error' => 'Gagal upload file'], 400);
    }
}
