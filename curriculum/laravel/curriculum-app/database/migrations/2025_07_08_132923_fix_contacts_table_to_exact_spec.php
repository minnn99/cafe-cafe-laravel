<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // sent_atカラムを削除（資料にない）
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn('sent_at');
        });
        
        // idをintに変更、timestampをdatetimeに変更
        Schema::table('contacts', function (Blueprint $table) {
            // Laravelのデフォルトのタイムスタンプカラムを削除
            $table->dropTimestamps();
            
            // 資料通りのdatetimeカラムを追加（NotNull）
            $table->dateTime('created_at')->comment('作成日時');
            $table->dateTime('updated_at')->comment('更新日時');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            // sent_atカラムを復元
            $table->timestamp('sent_at')->nullable();
            
            // datetimeカラムを削除
            $table->dropColumn(['created_at', 'updated_at']);
            
            // Laravelのデフォルトタイムスタンプを復元
            $table->timestamps();
        });
    }
};
