<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ContactFormRequest;
use App\Models\Contact;

class ContactController extends Controller
{
    /**
     * 問い合わせフォームを表示
     */
    public function index()
    {
        // 送信済みの問い合わせ一覧を取得（最新順）
        $contacts = Contact::orderBy('created_at', 'desc')->get();
        
        return view('contact.index', compact('contacts'));
    }

    /**
     * 確認画面から戻る処理
     */
    public function back(Request $request)
    {
        // セッションのデータを old input として設定
        $data = session('contact_data');
        
        if ($data) {
            // セッションデータを old input として flash
            return redirect()->route('contact.index')->withInput($data);
        }
        
        return redirect()->route('contact.index');
    }

    /**
     * フォームデータを処理して確認画面へ
     */
    public function store(ContactFormRequest $request)
    {
        // セッションにバリデーション済みデータを保存
        session(['contact_data' => $request->validated()]);
        
        return redirect()->route('contact.confirm');
    }

    /**
     * 確認画面を表示
     */
    public function confirm()
    {
        $data = session('contact_data');
        
        if (!$data) {
            return redirect()->route('contact.index')
                           ->with('error', 'セッションが切れました。もう一度入力してください。');
        }
        
        return view('contact.confirm', compact('data'));
    }

    /**
     * お問い合わせを送信
     */
    public function send(Request $request)
    {
        $data = session('contact_data');
        
        if (!$data) {
            return redirect()->route('contact.index')
                           ->with('error', 'セッションが切れました。もう一度入力してください。');
        }

        // データベースに保存
        $data['sent_at'] = now();
        Contact::create($data);

        // ここでメール送信処理を行う
        // Mail::to($data['email'])->send(new ContactMailConfirmation($data));
        // Mail::to(config('mail.admin_email'))->send(new ContactMailNotification($data));

        // セッションデータをクリア
        session()->forget('contact_data');

        return redirect()->route('contact.index')
                       ->with('success', 'お問い合わせを送信しました。ありがとうございます。')
                       ->with('form_submitted', true);
    }

    /**
     * 問い合わせの編集フォームを表示
     */
    public function edit($id)
    {
        // ダイレクトアクセス防止: 適切な参照元からのアクセスかチェック
        $referer = request()->header('referer');
        $contactIndexUrl = route('contact.index');
        
        // 参照元がお問い合わせ一覧ページでない場合は拒否
        if (!$referer || !str_contains($referer, $contactIndexUrl)) {
            return redirect()->route('contact.index')
                           ->with('error', '不正なアクセスです。お問い合わせ一覧から編集してください。');
        }

        $contact = Contact::findOrFail($id);
        
        // セッションに編集可能フラグを設定（CSRF攻撃防止）
        session(['can_edit_contact_' . $id => true]);
        
        return view('contact.edit', compact('contact'));
    }

    /**
     * 問い合わせの更新
     */
    public function update(ContactFormRequest $request, $id)
    {
        // セッションベースの編集権限チェック
        if (!session('can_edit_contact_' . $id)) {
            return redirect()->route('contact.index')
                           ->with('error', '不正なアクセスです。編集権限がありません。');
        }

        $contact = Contact::findOrFail($id);
        $contact->update($request->validated());
        
        // 編集完了後にセッションをクリア
        session()->forget('can_edit_contact_' . $id);
        
        // 編集完了時に送信完了画面を表示
        return redirect()->route('contact.index')
                       ->with('success', 'お問い合わせ内容を更新しました。ありがとうございます。')
                       ->with('form_submitted', true);
    }

    /**
     * 問い合わせの削除
     */
    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        
        return redirect()->route('contact.index');
    }
}
