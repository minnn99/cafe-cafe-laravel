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
        return view('contact.index');
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
                       ->with('success', 'お問い合わせを送信しました。ありがとうございます。');
    }
}
