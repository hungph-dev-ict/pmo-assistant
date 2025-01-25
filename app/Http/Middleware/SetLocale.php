<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('lang', config('app.locale'));  // Lấy ngôn ngữ từ session
        app()->setLocale($locale);  // Đặt ngôn ngữ cho ứng dụng
        return $next($request);
    }
}
