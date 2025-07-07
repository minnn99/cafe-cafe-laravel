<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    /**
     * 一括代入可能な属性
     */
    protected $fillable = [
        'name',
        'furigana',
        'phone',
        'email',
        'message',
        'sent_at',
    ];

    /**
     * 日付として扱う属性
     */
    protected $casts = [
        'sent_at' => 'datetime',
    ];
}
