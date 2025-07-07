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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 10)->comment('氏名');
            $table->string('furigana', 10)->comment('フリガナ');
            $table->string('phone')->nullable()->comment('電話番号');
            $table->string('email')->comment('メールアドレス');
            $table->text('message')->comment('お問い合わせ内容');
            $table->timestamp('sent_at')->nullable()->comment('送信日時');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
