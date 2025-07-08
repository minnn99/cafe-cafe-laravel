<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Contact;

class ContactEditDirectAccessTest extends TestCase
{
    use RefreshDatabase;

    private Contact $contact;

    public function setUp(): void
    {
        parent::setUp();
        
        // テスト用のお問い合わせデータを作成
        $this->contact = Contact::create([
            'name' => 'テスト太郎',
            'furigana' => 'テストタロウ',
            'phone' => '09012345678',
            'email' => 'test@example.com',
            'message' => 'テストメッセージです。',
            'sent_at' => now(),
        ]);
    }

    /** @test */
    public function 編集ページへのダイレクトアクセスは拒否される()
    {
        // ダイレクトアクセス（参照元なし）
        $response = $this->get(route('contact.edit', $this->contact->id));
        
        $response->assertRedirect(route('contact.index'));
        $response->assertSessionHas('error', '不正なアクセスです。お問い合わせ一覧から編集してください。');
    }

    /** @test */
    public function 不正な参照元からの編集ページアクセスは拒否される()
    {
        // 不正な参照元を設定
        $response = $this->withHeaders([
            'referer' => 'https://example.com/malicious'
        ])->get(route('contact.edit', $this->contact->id));
        
        $response->assertRedirect(route('contact.index'));
        $response->assertSessionHas('error', '不正なアクセスです。お問い合わせ一覧から編集してください。');
    }

    /** @test */
    public function 正しい参照元からの編集ページアクセスは許可される()
    {
        // 正しい参照元（お問い合わせ一覧ページ）からのアクセス
        $response = $this->withHeaders([
            'referer' => route('contact.index')
        ])->get(route('contact.edit', $this->contact->id));
        
        $response->assertOk();
        $response->assertViewIs('contact.edit');
        $response->assertViewHas('contact', $this->contact);
    }

    /** @test */
    public function セッション権限なしでの更新は拒否される()
    {
        $updateData = [
            'name' => '更新太郎',
            'furigana' => 'コウシンタロウ',
            'phone' => '09087654321',
            'email' => 'update@example.com',
            'message' => '更新されたメッセージです。'
        ];

        // セッション権限なしで更新を試行
        $response = $this->put(route('contact.update', $this->contact->id), $updateData);
        
        $response->assertRedirect(route('contact.index'));
        $response->assertSessionHas('error', '不正なアクセスです。編集権限がありません。');
        
        // データが更新されていないことを確認
        $this->contact->refresh();
        $this->assertEquals('テスト太郎', $this->contact->name);
    }

    /** @test */
    public function 正しいセッション権限での更新は成功する()
    {
        // まず編集ページに正しくアクセスしてセッション権限を取得
        $this->withHeaders([
            'referer' => route('contact.index')
        ])->get(route('contact.edit', $this->contact->id));

        $updateData = [
            'name' => '更新太郎',
            'furigana' => 'コウシンタロウ',
            'phone' => '09087654321',
            'email' => 'update@example.com',
            'message' => '更新されたメッセージです。'
        ];

        // セッション権限ありで更新
        $response = $this->put(route('contact.update', $this->contact->id), $updateData);
        
        $response->assertRedirect(route('contact.index'));
        
        // データが正しく更新されていることを確認
        $this->contact->refresh();
        $this->assertEquals('更新太郎', $this->contact->name);
        $this->assertEquals('update@example.com', $this->contact->email);
        
        // 更新後にセッション権限がクリアされていることを確認
        $this->assertNull(session('can_edit_contact_' . $this->contact->id));
    }

    /** @test */
    public function 存在しないお問い合わせの編集は404エラーになる()
    {
        $response = $this->withHeaders([
            'referer' => route('contact.index')
        ])->get(route('contact.edit', 99999));
        
        $response->assertNotFound();
    }
}
