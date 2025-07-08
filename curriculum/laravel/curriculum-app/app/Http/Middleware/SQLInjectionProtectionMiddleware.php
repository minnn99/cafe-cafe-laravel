<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SQLInjectionProtectionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // SQLインジェクションパターンの検出
        $dangerousPatterns = config('database_security.dangerous_patterns');
        
        // 全ての入力データをチェック
        $allInput = array_merge(
            $request->all(),
            $request->query(),
            $request->headers->all()
        );
        
        foreach ($allInput as $key => $value) {
            if (is_string($value) && $this->detectSQLInjection($value, $dangerousPatterns)) {
                // ログに記録
                Log::warning('SQLインジェクション攻撃を検出しました', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'url' => $request->url(),
                    'method' => $request->method(),
                    'suspicious_field' => $key,
                    'suspicious_value' => $value,
                    'timestamp' => now(),
                ]);
                
                // 攻撃をブロック
                return response()->json([
                    'error' => 'セキュリティ上の理由により、リクエストが拒否されました。',
                    'code' => 'SECURITY_VIOLATION'
                ], 403);
            }
        }
        
        return $next($request);
    }
    
    /**
     * SQLインジェクションパターンを検出
     */
    private function detectSQLInjection(string $input, array $patterns): bool
    {
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }
        
        return false;
    }
}
