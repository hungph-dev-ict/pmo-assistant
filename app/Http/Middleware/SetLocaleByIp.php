<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleByIp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip(); // Lấy địa chỉ IP của người dùng

        // Sử dụng API để lấy thông tin vị trí (quốc gia) của người dùng từ địa chỉ IP
        $response = file_get_contents("http://ip-api.com/json/{$ip}");
        $data = json_decode($response, true);

        if (isset($data['countryCode'])) {
            $countryCode = $data['countryCode'];

            // Thiết lập ngôn ngữ mặc định dựa trên quốc gia
            if ($countryCode == 'VN') {
                app()->setLocale('vi');
            } elseif ($countryCode == 'JP') {
                app()->setLocale('jp');
            } else {
                app()->setLocale('en'); // Mặc định là tiếng Anh nếu không xác định được
            }
        }

        return $next($request);
    }
}
