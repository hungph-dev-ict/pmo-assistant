<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Chuyển đổi ảnh thành base64
     *
     * @param string|null $imageLink Link ảnh cần chuyển đổi
     * @param string $defaultLink Link mặc định nếu $imageLink null
     * @return string Base64 của ảnh
     */
    public static function imageToBase64(?string $imageLink): string
    {
        if (filter_var($imageLink, FILTER_VALIDATE_URL)) {
            // Link là URL, tải ảnh từ internet
            $imageContent = file_get_contents($imageLink);
        } else {
            // Link là file local
            $imageContent = file_exists($imageLink) ? file_get_contents($imageLink) : null;
        }

        if ($imageContent) {
            return 'data:image/jpeg;base64,' . base64_encode($imageContent);
        }

        return ''; // Trả về chuỗi rỗng nếu không tìm thấy ảnh
    }
}
