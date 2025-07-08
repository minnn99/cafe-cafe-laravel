<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Contact extends Model
{
    /**
     * 一括代入可能な属性
     */
    protected $fillable = [
        'name',
        'kana',
        'tel',
        'email',
        'body',
    ];

    /**
     * 日付として扱う属性
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    /**
     * モデルの保存前にセキュリティチェックを実行
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($contact) {
            $contact->validateSecurityConstraints();
            $contact->logSecurityAction('creating');
        });
        
        static::updating(function ($contact) {
            $contact->validateSecurityConstraints();
            $contact->logSecurityAction('updating');
        });
    }
    
    /**
     * セキュリティ制約をバリデーション
     */
    private function validateSecurityConstraints()
    {
        $dangerousPatterns = [
            '/(\bUNION\b|\bSELECT\b|\bINSERT\b|\bUPDATE\b|\bDELETE\b)/i',
            '/(\bDROP\b|\bCREATE\b|\bALTER\b|\bTRUNCATE\b)/i',
            '/[;<>\'"`]/i',
        ];
        
        foreach ($this->fillable as $field) {
            if (isset($this->attributes[$field])) {
                $value = $this->attributes[$field];
                
                foreach ($dangerousPatterns as $pattern) {
                    if (is_string($value) && preg_match($pattern, $value)) {
                        Log::alert('モデルレベルでSQLインジェクション攻撃を検出', [
                            'model' => get_class($this),
                            'field' => $field,
                            'value' => $value,
                            'pattern' => $pattern,
                            'timestamp' => now(),
                        ]);
                        
                        throw new \InvalidArgumentException(
                            "セキュリティ違反: フィールド '{$field}' に不正な値が検出されました。"
                        );
                    }
                }
            }
        }
    }
    
    /**
     * セキュリティアクションをログに記録
     */
    private function logSecurityAction(string $action)
    {
        Log::info("Contact model security action: {$action}", [
            'model' => get_class($this),
            'action' => $action,
            'attributes' => $this->attributes,
            'timestamp' => now(),
            'ip' => request()->ip() ?? 'unknown',
        ]);
    }
    
    /**
     * 安全な検索機能
     */
    public static function safeSearch(array $criteria, int $limit = 100)
    {
        $query = static::query();
        
        // 最大結果数を制限
        $limit = min($limit, config('database_security.max_result_limit', 1000));
        
        foreach ($criteria as $field => $value) {
            if (in_array($field, (new static)->fillable) && !empty($value)) {
                // 安全な LIKE 検索（エスケープ処理）
                $query->where($field, 'LIKE', '%' . addslashes($value) . '%');
            }
        }
        
        return $query->limit($limit)->get();
    }
}
