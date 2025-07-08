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
        Schema::table('contacts', function (Blueprint $table) {
            // カラム名とサイズを資料の仕様に合わせて変更
            $table->renameColumn('furigana', 'kana');
            $table->renameColumn('phone', 'tel');
            $table->renameColumn('message', 'body');
        });
        
        // カラムのサイズ変更（別のスキーマ変更として実行）
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('name', 50)->change()->comment('氏名');
            $table->string('kana', 50)->change()->comment('フリガナ');
            $table->string('tel', 11)->nullable()->change()->comment('電話番号');
            $table->string('email', 100)->change()->comment('メールアドレス');
            $table->text('body')->change()->comment('お問い合わせ内容');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            // 元のカラム名に戻す
            $table->renameColumn('kana', 'furigana');
            $table->renameColumn('tel', 'phone');
            $table->renameColumn('body', 'message');
        });
        
        // 元のサイズに戻す
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('name', 10)->change()->comment('氏名');
            $table->string('furigana', 10)->change()->comment('フリガナ');
            $table->string('phone')->nullable()->change()->comment('電話番号');
            $table->string('email')->change()->comment('メールアドレス');
            $table->text('message')->change()->comment('お問い合わせ内容');
        });
    }
};
