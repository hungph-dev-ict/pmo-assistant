<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\RequestLog;

class LogRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Ghi thông tin vào bảng request_logs
        RequestLog::create([
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'headers' => json_encode($request->headers->all()),
            'body' => json_encode($request->all()),
        ]);

        // Tiếp tục xử lý request
        return $next($request);
    }
}
