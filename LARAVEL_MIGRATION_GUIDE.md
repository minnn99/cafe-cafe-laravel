# Laravel 마이그레이션 가이드

## 📋 프로젝트 개요

현재 PHP 순수 코드로 작성된 CafeCafe 웹사이트를 Laravel 프레임워크로 마이그레이션하는 단계별 가이드입니다.

## 🎯 마이그레이션 목표

- ✅ MVC 패턴 적용으로 코드 구조 개선
- ✅ Laravel의 강력한 기능 활용 (라우팅, 미들웨어, Eloquent ORM 등)
- ✅ 기존 기능 유지하면서 확장성 향상
- ✅ Docker 환경 호환성 유지

---

## 🚀 STEP 1: Laravel 프로젝트 초기 설정

### 1.1 Laravel 프로젝트 생성

```bash
# 새로운 Laravel 프로젝트 생성
cd /Users/minjae/Desktop/NNC/7_PHP応用/educure-CafeCafe/curriculum/laravel/
composer create-project laravel/laravel cafecafe-laravel

# 또는 기존 curriculum-app 폴더를 백업하고 교체
mv curriculum-app curriculum-app-backup
composer create-project laravel/laravel curriculum-app
```

### 1.2 환경 설정

```bash
cd curriculum-app
cp .env.example .env
php artisan key:generate
```

### 1.3 .env 파일 설정

```env
APP_NAME="CafeCafe"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8080

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=cafe
DB_USERNAME=root
DB_PASSWORD=root

SESSION_DRIVER=file
SESSION_LIFETIME=120
```

### 1.4 Docker 설정 업데이트

```yaml
# docker-compose.yml에 Laravel 지원을 위한 PHP 설정 추가
version: "3.8"
services:
  php:
    build: ./php
    volumes:
      - ./curriculum/laravel/curriculum-app:/var/www/html
    environment:
      - LARAVEL_PROCS_NUMBER=1

  nginx:
    image: nginx:latest
    ports:
      - "8080:80"
    volumes:
      - ./curriculum/laravel/curriculum-app/public:/var/www/html/public
      - ./nginx/config:/etc/nginx/conf.d
    depends_on:
      - php
      - mysql
```

---

## 🏗️ STEP 2: 기본 구조 마이그레이션 (1-2일)

### 2.1 라우트 설정

```php
// routes/web.php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// 문의 관련 라우트
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/contact/confirm', [ContactController::class, 'confirm'])->name('contact.confirm');
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');
```

### 2.2 컨트롤러 생성

```bash
php artisan make:controller HomeController
php artisan make:controller ContactController
```

### 2.3 뷰 템플릿 구조

```
resources/views/
├── layouts/
│   └── app.blade.php        # 기본 레이아웃 (header + footer)
├── components/
│   ├── header.blade.php     # 헤더 컴포넌트
│   ├── footer.blade.php     # 푸터 컴포넌트
│   └── login-modal.blade.php # 로그인 모달
├── home/
│   └── index.blade.php      # 홈페이지
└── contact/
    ├── index.blade.php      # 문의 폼
    └── confirm.blade.php    # 확인 페이지
```

---

## 📝 STEP 3: 문의 기능 마이그레이션 (2일)

### 3.1 마이그레이션 파일 생성

```bash
php artisan make:migration create_contacts_table
```

```php
// database/migrations/xxxx_create_contacts_table.php
public function up()
{
    Schema::create('contacts', function (Blueprint $table) {
        $table->id();
        $table->string('name', 10);
        $table->string('furigana', 10);
        $table->string('phone')->nullable();
        $table->string('email');
        $table->text('message');
        $table->timestamp('sent_at')->nullable();
        $table->timestamps();
    });
}
```

### 3.2 모델 생성

```bash
php artisan make:model Contact
```

### 3.3 폼 리퀘스트 클래스 생성

```bash
php artisan make:request ContactFormRequest
```

```php
// app/Http/Requests/ContactFormRequest.php
public function rules()
{
    return [
        'name' => 'required|max:10',
        'furigana' => 'required|max:10|regex:/^[ァ-ヶー]+$/u',
        'phone' => 'nullable|regex:/^[0-9-]+$/',
        'email' => 'required|email',
        'message' => 'required',
    ];
}

public function messages()
{
    return [
        'name.required' => '氏名は必須入力です。',
        'name.max' => '氏名は10文字以内で入力してください。',
        'furigana.required' => 'フリガナは必須入力です。',
        'furigana.max' => 'フリガナは10文字以内で入力してください。',
        'furigana.regex' => 'フリガナはカタカナで入力してください。',
        'phone.regex' => '電話番号には半角数字しか入力出来ません。',
        'email.required' => 'メールアドレスは必須入力です。',
        'email.email' => 'メールアドレスは正しい形式でしか入力出来ません。',
        'message.required' => 'お問い合わせ内容は必須入力です。',
    ];
}
```

### 3.4 컨트롤러 구현

```php
// app/Http/Controllers/ContactController.php
class ContactController extends Controller
{
    public function index()
    {
        return view('contact.index');
    }

    public function store(ContactFormRequest $request)
    {
        // セッションにデータを保存して確認画面へ
        session(['contact_data' => $request->validated()]);
        return redirect()->route('contact.confirm');
    }

    public function confirm()
    {
        $data = session('contact_data');
        if (!$data) {
            return redirect()->route('contact.index');
        }
        return view('contact.confirm', compact('data'));
    }

    public function send(Request $request)
    {
        $data = session('contact_data');
        if (!$data) {
            return redirect()->route('contact.index');
        }

        // データベースに保存
        Contact::create($data);

        // メール送信処理など...

        // 成功メッセージと共にリダイレクト
        session()->forget('contact_data');
        return redirect()->route('contact.index')->with('success', 'お問い合わせを送信しました。');
    }
}
```

---

## 🎨 STEP 4: フロントエンド リソース 移行 (1일)

### 4.1 アセット移行

```bash
# 現在のリソースをLaravelの適切な場所に移動
cp -r public/css/ curriculum-app/public/css/
cp -r public/js/ curriculum-app/public/js/
cp -r public/img/ curriculum-app/public/img/
```

### 4.2 Blade テンプレート作成

```blade
{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CafeCafe')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    @include('components.header')

    @yield('content')

    @include('components.footer')
    @include('components.login-modal')

    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
```

### 4.3 コンポーネント分離

```blade
{{-- resources/views/contact/index.blade.php --}}
@extends('layouts.app')

@section('title', 'お問い合わせ - CafeCafe')

@section('content')
<main class="main-content">
    <section class="contact-section">
        <div class="contact-form-container">
            <h1 class="contact-title">お問い合わせ</h1>

            @if(session('success'))
                <div class="success-message">
                    {{ session('success') }}
                </div>
            @else
                <h2 class="form-subtitle">下記の項目をご記入の上送信ボタンを押してください</h2>

                <form action="{{ route('contact.store') }}" method="POST" id="contactForm">
                    @csrf

                    <div class="form-group">
                        <label for="name">氏名<span class="required">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" maxlength="10">
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- 他のフィールドも同様に... --}}

                    <button type="submit" class="btn-submit">内容を確認する</button>
                </form>
            @endif
        </div>
    </section>
</main>
@endsection
```

---

## 🔧 STEP 5: 認証機能 追加 (2-3일)

### 5.1 Laravel Breeze インストール (簡単な認証)

```bash
composer require laravel/breeze --dev
php artisan breeze:install
npm install && npm run dev
```

### 5.2 ソーシャルログイン設定

```bash
composer require laravel/socialite
```

```php
// config/services.php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URL'),
],
'facebook' => [
    'client_id' => env('FACEBOOK_CLIENT_ID'),
    'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
    'redirect' => env('FACEBOOK_REDIRECT_URL'),
],
```

---

## 🗄️ STEP 6: データベース設計 (1일)

### 6.1 追加テーブル設計

```bash
# ユーザー関連
php artisan make:migration add_social_login_to_users_table

# カフェ情報関連（将来の拡張）
php artisan make:migration create_cafes_table
php artisan make:migration create_menu_items_table
```

---

## 🧪 STEP 7: テスト & デバッグ (1-2일)

### 7.1 機能テスト作成

```bash
php artisan make:test ContactFormTest
```

### 7.2 Docker環境での動作確認

```bash
docker-compose up -d
php artisan migrate
php artisan serve --host=0.0.0.0 --port=8000
```

---

## 🚀 STEP 8: デプロイ準備 (1日)

### 8.1 本番環境設定

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 8.2 Docker本番設定

```dockerfile
# Dockerfile for production
FROM php:8.2-fpm
RUN docker-php-ext-install pdo pdo_mysql
COPY . /var/www/html
WORKDIR /var/www/html
RUN composer install --optimize-autoloader --no-dev
```

---

## 📊 想定工数とスケジュール

| ステップ | 作業内容                 | 想定工数 | 担当者スキルレベル |
| -------- | ------------------------ | -------- | ------------------ |
| STEP 1   | Laravel環境構築          | 1日      | 初級〜中級         |
| STEP 2   | 基本構造マイグレーション | 1-2日    | 中級               |
| STEP 3   | 文問い合わせ機能移行     | 2日      | 中級               |
| STEP 4   | フロントエンド移行       | 1日      | 初級〜中級         |
| STEP 5   | 認証機能追加             | 2-3日    | 中級〜上級         |
| STEP 6   | データベース設計         | 1日      | 中級               |
| STEP 7   | テスト&デバッグ          | 1-2日    | 中級               |
| STEP 8   | デプロイ準備             | 1日      | 中級               |

**総工数: 約 9-12日 (約2週間)**

---

## 🎯 マイグレーション後の利点

### 1. コード品質向上

- **MVC パターン**: より明確な責任分離
- **バリデーション**: Laravel の Form Request 活用
- **テスト**: PHPUnit による自動テスト
- **エラーハンドリング**: Laravel の例外処理機能

### 2. 開発効率向上

- **Artisan コマンド**: コード生成の自動化
- **Eloquent ORM**: データベース操作の簡略化
- **Blade テンプレート**: 再利用可能なコンポーネント
- **ミドルウェア**: 認証・CSRF 保護の簡単実装

### 3. スケーラビリティ

- **ルーティング**: RESTful な URL 設計
- **キュー**: 非同期処理（メール送信等）
- **キャッシュ**: パフォーマンス向上
- **多言語対応**: Laravel の国際化機能

### 4. セキュリティ強化

- **CSRF 保護**: 自動的に適用
- **SQL インジェクション対策**: Eloquent による安全なクエリ
- **XSS 対策**: Blade の自動エスケープ
- **認証**: Laravel Sanctum/Passport 対応

---

## 🚨 注意点とリスク

### 1. 学習コスト

- Laravel フレームワークの習得時間
- Composer、Artisan コマンドの理解
- Blade テンプレートエンジンの習得

### 2. 移行リスク

- 既存機能の動作確認
- データの整合性
- フロントエンドの互換性

### 3. 環境依存

- PHP バージョン要件 (Laravel 10.x は PHP 8.1+)
- Composer の依存関係
- Docker 設定の調整

---

## 💡 推奨アプローチ

### Phase 1: 基本機能移行 (1週間)

1. Laravel 環境構築
2. 文問い合わせ機能のみ移行
3. 基本動作確認

### Phase 2: 機能拡張 (1週間)

1. 認証機能追加
2. ソーシャルログイン実装
3. UI/UX 改善

### Phase 3: 本格運用準備 (1週間)

1. テスト実装
2. パフォーマンス最適化
3. デプロイ準備

---

## 📞 次のステップ

1. **Laravel 学習**: 公式ドキュメント、チュートリアル
2. **環境構築**: ローカル開発環境でのテスト
3. **段階的移行**: 機能毎の部分的移行
4. **継続的テスト**: 各段階での動作確認

このガイドに沿って進めることで、現在の PHP プロジェクトを Laravel に安全かつ効率的に移行できます。
