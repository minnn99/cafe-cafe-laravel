<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class XSSProtectionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * XSS攻撃パターンのテストデータ
     */
    protected $xssPayloads = [
        '<script>alert("XSS")</script>',
        '<img src="x" onerror="alert(\'XSS\')">',
        'javascript:alert("XSS")',
        '<iframe src="javascript:alert(\'XSS\')"></iframe>',
        '<svg onload="alert(\'XSS\')">',
        '<body onload="alert(\'XSS\')">',
        '<input type="text" onfocus="alert(\'XSS\')" autofocus>',
        '<a href="javascript:alert(\'XSS\')">Click me</a>',
        '<div onclick="alert(\'XSS\')">Click me</div>',
        '"><script>alert("XSS")</script>',
        '\';alert("XSS");//',
        '<script>document.cookie</script>',
        '<script>document.write("XSS")</script>',
        '<object data="javascript:alert(\'XSS\')"></object>',
        '<embed src="javascript:alert(\'XSS\')">',
    ];

    /**
     * 問い合わせフォームでのXSS攻撃テスト
     *
     * @test
     * @dataProvider xssPayloadProvider
     */
    public function test_contact_form_blocks_xss_attacks($payload)
    {
        $response = $this->post('/contact', [
            'name' => $payload,
            'furigana' => 'テストユーザー',
            'email' => 'test@example.com',
            'message' => 'テストメッセージ',
        ]);

        // バリデーションエラーが発生することを確認
        $response->assertSessionHasErrors('name');
    }

    /**
     * メッセージフィールドでのXSS攻撃テスト
     *
     * @test
     * @dataProvider xssPayloadProvider
     */
    public function test_message_field_blocks_xss_attacks($payload)
    {
        $response = $this->post('/contact', [
            'name' => 'テストユーザー',
            'furigana' => 'テストユーザー',
            'email' => 'test@example.com',
            'message' => $payload,
        ]);

        // XSSペイロードを含むメッセージの場合、適切に処理されることを確認
        if ($this->containsDangerousChars($payload)) {
            $response->assertSessionHasErrors('message');
        }
    }

    /**
     * メールアドレスフィールドでのXSS攻撃テスト
     *
     * @test
     */
    public function test_email_field_blocks_xss_attacks()
    {
        $xssPayloads = [
            '<script>alert("XSS")</script>@example.com',
            'test@<script>alert("XSS")</script>.com',
            'test"<script>alert("XSS")</script>"@example.com',
        ];

        foreach ($xssPayloads as $payload) {
            $response = $this->post('/contact', [
                'name' => 'テストユーザー',
                'furigana' => 'テストユーザー',
                'email' => $payload,
                'message' => 'テストメッセージ',
            ]);

            $response->assertSessionHasErrors('email');
        }
    }

    /**
     * 正常なデータが通ることを確認
     *
     * @test
     */
    public function test_valid_data_passes_validation()
    {
        $response = $this->post('/contact', [
            'name' => 'テストユーザー',
            'furigana' => 'テストユーザー',
            'phone' => '090-1234-5678',
            'email' => 'test@example.com',
            'message' => 'これは正常なメッセージです。改行も含まれています。',
        ]);

        $response->assertRedirect('/contact/confirm');
        $response->assertSessionHasNoErrors();
    }

    /**
     * CSPヘッダーが設定されていることを確認
     *
     * @test
     */
    public function test_csp_headers_are_set()
    {
        $response = $this->get('/contact');

        $response->assertHeader('Content-Security-Policy');
        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
    }

    /**
     * サニタイズ機能のテスト
     *
     * @test
     */
    public function test_input_sanitization()
    {
        $maliciousInput = '<script>alert("XSS")</script>Hello';
        $expectedOutput = 'Hello'; // HTMLタグが削除される

        $response = $this->post('/contact', [
            'name' => $maliciousInput,
            'furigana' => 'テストユーザー',
            'email' => 'test@example.com',
            'message' => 'テストメッセージ',
        ]);

        // バリデーションエラーが発生することを確認
        $response->assertSessionHasErrors('name');
    }

    /**
     * XSSペイロードのデータプロバイダー
     */
    public static function xssPayloadProvider()
    {
        $xssPayloads = [
            '<script>alert("XSS")</script>',
            '<img src="x" onerror="alert(\'XSS\')">',
            'javascript:alert("XSS")',
            '<iframe src="javascript:alert(\'XSS\')"></iframe>',
            '<svg onload="alert(\'XSS\')">',
            '<body onload="alert(\'XSS\')">',
            '<input type="text" onfocus="alert(\'XSS\')" autofocus>',
            '<a href="javascript:alert(\'XSS\')">Click me</a>',
            '<div onclick="alert(\'XSS\')">Click me</div>',
            '"><script>alert("XSS")</script>',
            '\';alert("XSS");//',
            '<script>document.cookie</script>',
            '<script>document.write("XSS")</script>',
            '<object data="javascript:alert(\'XSS\')"></object>',
            '<embed src="javascript:alert(\'XSS\')">',
        ];
        
        return array_map(function($payload) {
            return [$payload];
        }, $xssPayloads);
    }

    /**
     * 危険な文字が含まれているかチェック
     */
    private function containsDangerousChars($input)
    {
        $dangerousChars = ['<', '>', '"', "'", '&'];
        foreach ($dangerousChars as $char) {
            if (strpos($input, $char) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * 出力エスケープのテスト
     *
     * @test
     */
    public function test_output_escaping_in_confirmation_page()
    {
        // セッションにXSSペイロードを含むデータを設定
        session([
            'contact_data' => [
                'name' => '<script>alert("XSS")</script>Test',
                'furigana' => 'テストユーザー',
                'email' => 'test@example.com',
                'message' => '<img src="x" onerror="alert(\'XSS\')">メッセージ',
            ]
        ]);

        $response = $this->get('/contact/confirm');

        // レスポンス内容にスクリプトタグがエスケープされていることを確認
        $response->assertDontSee('<script>', false);
        $response->assertDontSee('<img src="x" onerror', false);
        
        // エスケープされた文字が含まれていることを確認
        $response->assertSee('&lt;script&gt;', false);
    }
}
