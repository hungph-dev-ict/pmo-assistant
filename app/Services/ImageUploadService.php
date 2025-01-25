<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Tenant;

class ImageUploadService
{
    public function uploadToGoogleDrive($folder, $file)
    {
        try {
            // Check if file is null
            if (!$file) {
                Log::error('No file uploaded.');
                return null;
            }

            // Tạo tên file duy nhất
            $filename = time() . '_' . $file->getClientOriginalName();

            // Upload file lên Google Drive
            $path = Storage::disk('google')->putFileAs($folder, $file, $filename);

            // Lấy URL của file trên Google Drive sau khi tải lên thành công
            $fileUrl = Storage::disk('google')->url($path);

            // Trả về URL của file trên Google Drive
            return $fileUrl;
        } catch (\Exception $e) {
            Log::error('Upload to Google Drive failed: ' . $e->getMessage());
            return null;
        }
    }
}

