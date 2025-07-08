<?php

return [
    /*
    |--------------------------------------------------------------------------
    | データベースセキュリティ設定
    |--------------------------------------------------------------------------
    |
    | SQLインジェクション対策とデータベースセキュリティの強化設定
    |
    */

    // SQLクエリログ設定（本番環境では無効化推奨）
    'log_queries' => env('DB_LOG_QUERIES', false),
    
    // 危険なSQL文の検出パターン
    'dangerous_patterns' => [
        // SQLインジェクション関連
        '/(\bUNION\b|\bSELECT\b|\bINSERT\b|\bUPDATE\b|\bDELETE\b)/i',
        '/(\bDROP\b|\bCREATE\b|\bALTER\b|\bTRUNCATE\b)/i',
        '/(\bEXEC\b|\bEXECUTE\b|\bsp_\b)/i',
        
        // 特殊文字・メタ文字
        '/[;<>\'"`|&${}()]/i',
        
        // スクリプト注入
        '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi',
        '/javascript:/i',
    ],
    
    // 最大クエリ実行時間（秒）
    'max_execution_time' => 30,
    
    // 最大結果セット数
    'max_result_limit' => 1000,
];
