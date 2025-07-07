@extends('layouts.app')

@section('title', 'XSS対策テスト - CafeCafe')

@section('content')
<section class="contact-section">
    <div class="contact-form-container">
        <h1 class="contact-title">🔒 XSS対策テストページ</h1>
        <p>このページでは、実装されたXSS対策の動作を確認できます。</p>

        <div style="background: #f8f9fa; padding: 20px; margin: 20px 0; border-radius: 8px;">
            <h3>📋 テスト手順:</h3>
            <ol>
                <li><strong>ブラウザの開発者ツール</strong>を開く（F12キー）</li>
                <li><strong>Consoleタブ</strong>を選択</li>
                <li>下記のフォームに<strong>XSSペイロード</strong>を入力</li>
                <li>右下の<strong>「XSS対策テストを実行」</strong>ボタンをクリック</li>
                <li><strong>コンソール</strong>でテスト結果を確認</li>
            </ol>
        </div>

        <div style="background: #fff3cd; padding: 15px; margin: 20px 0; border-radius: 8px; border-left: 4px solid #ffc107;">
            <h4>⚠️ テスト用XSSペイロード例:</h4>
            <ul style="font-family: monospace; font-size: 14px;">
                <li><code>&lt;script&gt;alert("XSS")&lt;/script&gt;</code></li>
                <li><code>&lt;img src="x" onerror="alert('XSS')"&gt;</code></li>
                <li><code>javascript:alert("XSS")</code></li>
                <li><code>&lt;svg onload="alert('XSS')"&gt;</code></li>
                <li><code>onload="alert('XSS')"</code></li>
            </ul>
            <p style="margin-top: 10px; color: #856404;">
                <strong>注意:</strong> これらのペイロードは学習目的でのみ使用してください。
            </p>
        </div>

        <!-- テスト用フォーム -->
        <form action="#" method="POST" class="contact-form" id="contactForm">
            @csrf
            
            <div class="form-group">
                <label for="name">氏名（XSSテスト用）<span class="required">*</span></label>
                <input type="text" id="name" name="name" 
                       maxlength="10" 
                       pattern="[^<>\"'&]*" 
                       title="HTML特殊文字（&lt; &gt; &quot; ' &amp;）は使用できません" 
                       placeholder="ここにXSSペイロードを入力してテスト">
                <span class="error-message" id="name-error"></span>
            </div>

            <div class="form-group">
                <label for="furigana">フリガナ（XSSテスト用）<span class="required">*</span></label>
                <input type="text" id="furigana" name="furigana" 
                       maxlength="10" 
                       pattern="[ァ-ヶー]+" 
                       title="カタカナのみ入力可能です" 
                       placeholder="テストユーザー">
                <span class="error-message" id="furigana-error"></span>
            </div>

            <div class="form-group">
                <label for="email">メールアドレス（XSSテスト用）<span class="required">*</span></label>
                <input type="email" id="email" name="email" 
                       pattern="[^<>\"'&]*@[^<>\"'&]*"
                       title="有効なメールアドレスを入力してください（HTML特殊文字は使用不可）"
                       placeholder="test@example.com">
                <span class="error-message" id="email-error"></span>
            </div>

            <div class="form-group">
                <label for="message">メッセージ（XSSテスト用）<span class="required">*</span></label>
                <textarea id="message" name="message" rows="8" 
                          maxlength="1000"
                          placeholder="ここにXSSペイロードを入力してテスト"></textarea>
                <span class="error-message" id="message-error"></span>
            </div>

            <button type="button" onclick="testCurrentForm()" class="btn-submit" style="background: #dc3545;">
                🧪 現在の入力値をテスト
            </button>
        </form>

        <div style="background: #d1ecf1; padding: 20px; margin: 20px 0; border-radius: 8px;">
            <h3>🛡️ 実装されている対策:</h3>
            <ul>
                <li><strong>サーバーサイド:</strong> HTMLspecialchars, strip_tags, バリデーション</li>
                <li><strong>クライアントサイド:</strong> リアルタイム入力検証</li>
                <li><strong>CSPヘッダー:</strong> Content Security Policy</li>
                <li><strong>セキュリティヘッダー:</strong> X-Frame-Options, X-XSS-Protection等</li>
                <li><strong>出力エスケープ:</strong> Blade自動エスケープ</li>
            </ul>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="/contact" class="btn-submit" style="display: inline-block; text-decoration: none;">
                📝 実際の問い合わせフォームに戻る
            </a>
        </div>
    </div>
</section>

<script>
// 現在のフォーム入力値をテストする関数
function testCurrentForm() {
    const form = document.getElementById('contactForm');
    const inputs = form.querySelectorAll('input, textarea');
    
    console.log('🔍 現在の入力値をテスト中...');
    
    inputs.forEach(input => {
        if (input.value.trim()) {
            const isBlocked = testXSSPayload(input.value);
            console.log(`フィールド "${input.name}": "${input.value}" - ${isBlocked ? '🛡️ ブロック' : '✅ 通過'}`);
            
            if (isBlocked) {
                // エラーを表示
                const errorElement = document.getElementById(input.name + '-error');
                if (errorElement) {
                    errorElement.textContent = 'XSS攻撃パターンが検出されました';
                    errorElement.classList.add('show');
                }
                input.classList.add('error');
            }
        }
    });
}

// XSSペイロードをテストする関数（xss-test.jsと同じ）
function testXSSPayload(payload) {
    const xssPatterns = [
        /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,
        /<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/gi,
        /<object\b[^<]*(?:(?!<\/object>)<[^<]*)*<\/object>/gi,
        /<embed\b[^<]*>/gi,
        /<form\b[^<]*(?:(?!<\/form>)<[^<]*)*<\/form>/gi,
        /javascript:/gi,
        /vbscript:/gi,
        /onload=/gi,
        /onclick=/gi,
        /onmouseover=/gi,
        /onerror=/gi,
        /alert\s*\(/gi,
        /document\.cookie/gi,
        /document\.write/gi,
        /eval\s*\(/gi
    ];

    if (!payload) return false;
    
    for (let pattern of xssPatterns) {
        if (pattern.test(payload)) {
            return true;
        }
    }
    
    const dangerousChars = ['<', '>', '"', "'", '&'];
    for (let char of dangerousChars) {
        if (payload.includes(char)) {
            return true;
        }
    }
    
    return false;
}
</script>

<script src="{{ asset('js/xss-test.js') }}"></script>
@endsection
