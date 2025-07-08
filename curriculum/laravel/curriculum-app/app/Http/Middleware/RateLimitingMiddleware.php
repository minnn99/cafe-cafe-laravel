<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RateLimitingMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $key = "rate_limit:{$ip}";
        $maxAttempts = 10; // 10分間で最大10回
        $decayMinutes = 10;
        
        $attempts = Cache::get($key, 0);
        
        if ($attempts >= $maxAttempts) {
            Log::warning('レート制限に達しました', [
                'ip' => $ip,
                'attempts' => $attempts,
                'url' => $request->url(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now(),
            ]);
            
            return response()->json([
                'error' => 'リクエスト回数が上限に達しました。しばらく時間をおいてから再度お試しください。',
                'retry_after' => $decayMinutes * 60
            ], 429);
        }
        
        Cache::put($key, $attempts + 1, now()->addMinutes($decayMinutes));
        
        return $next($request);
    }
}
