<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class ImageHelper
{
    /**
     * Chuyển đổi ảnh thành base64
     *
     * @param string|null $imageLink Link ảnh cần chuyển đổi
     * @return string Base64 của ảnh
     */
    public static function imageToBase64(?string $imageLink): string
    {
        // Nếu là ảnh local
        if (!filter_var($imageLink, FILTER_VALIDATE_URL)) {
            $imageContent = file_exists($imageLink) ? file_get_contents($imageLink) : null;
            return $imageContent ? 'data:image/jpeg;base64,' . base64_encode($imageContent) : '';
        }

        // Nếu là ảnh Google Drive
        if (strpos($imageLink, 'drive.google.com') !== false) {
            return self::getGoogleDriveImageBase64(self::extractGoogleDriveFileId($imageLink));
        }

        // Nếu là ảnh từ URL bình thường
        $response = Http::get($imageLink);
        return $response->successful() ? 'data:image/jpeg;base64,' . base64_encode($response->body()) : '';
    }

    /**
     * Lấy ảnh từ Google Drive
     *
     * @param string $fileId ID của file trên Google Drive
     * @return string Base64 của ảnh
     */
    public static function getGoogleDriveImageBase64(string $fileId): string
    {
        $accessToken = self::getGoogleAccessToken();
        if (!$accessToken) {
            return '';
        }

        $url = "https://www.googleapis.com/drive/v3/files/{$fileId}?alt=media";
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$accessToken}",
        ])->get($url);

        return $response->successful() ? 'data:image/jpeg;base64,' . base64_encode($response->body()) : '';
    }

    /**
     * Lấy Access Token từ Refresh Token của Google OAuth2
     *
     * @return string|null Access Token hoặc null nếu thất bại
     */
    public static function getGoogleAccessToken(): ?string
    {
        $clientId = env('GOOGLE_DRIVE_CLIENT_ID');
        $clientSecret = env('GOOGLE_DRIVE_CLIENT_SECRET');
        $refreshToken = env('GOOGLE_DRIVE_REFRESH_TOKEN');

        $url = 'https://oauth2.googleapis.com/token';
        $data = [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'refresh_token' => $refreshToken,
            'grant_type' => 'refresh_token',
        ];

        $response = Http::asForm()->post($url, $data);
        return $response->json()['access_token'] ?? null;
    }

    /**
     * Trích xuất Google Drive File ID từ URL
     *
     * @param string $url URL Google Drive
     * @return string File ID
     */
    public static function extractGoogleDriveFileId(string $url): string
    {
        preg_match('/(?:id=|\/d\/|open\?id=)([a-zA-Z0-9_-]+)/', $url, $matches);
        return $matches[1] ?? '';
    }
}
