@extends('layouts.app')

@section('title', 'お問い合わせ編集 - CafeCafe')

@section('content')
{{-- セッション権限チェック --}}
@if(!session('can_edit_contact_' . $contact->id))
    <section class="contact-section">
        <div class="contact-form-container">
            <div class="error-message">
                <p>不正なアクセスです。お問い合わせ一覧から編集してください。</p>
                <p><a href="{{ route('contact.index') }}" class="back-link">お問い合わせ一覧に戻る</a></p>
            </div>
        </div>
    </section>
@else
<section class="contact-section edit-page">
    <div class="contact-form-container">
        <h1 class="contact-title">お問い合わせ編集</h1>
        <h2 class="form-subtitle">下記の項目を修正の上更新ボタンを押してください</h2>
        
        <div class="contact-info">
            <p>送信頂いた件につきましては、当社より折り返しご連絡を差し上げます。</p>
            <p>なお、ご連絡までに、お時間を頂く場合もございますので予めご了承ください。</p>
            <p><span class="required">*</span>は必須項目となります。</p>
        </div>

        <form class="contact-form" method="POST" action="{{ route('contact.update', $contact->id) }}" novalidate>
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="name" class="form-label">
                    氏名<span class="required">*</span>
                </label>
                <div class="error-text" id="name-error">{{ $errors->first('name') }}</div>
                <input type="text" placeholder="山田太郎" id="name" name="name" class="form-input" value="{{ old('name', $contact->name) }}" required>
            </div>

            <div class="form-group">
                <label for="furigana" class="form-label">
                    フリガナ<span class="required">*</span>
                </label>
                <div class="error-text" id="furigana-error">{{ $errors->first('furigana') }}</div>
                <input type="text" placeholder="ヤマダタロウ" id="furigana" name="furigana" class="form-input" value="{{ old('furigana', $contact->furigana) }}" required>
            </div>

            <div class="form-group">
                <label for="phone" class="form-label">
                    電話番号
                </label>
                <div class="error-text" id="phone-error">{{ $errors->first('phone') }}</div>
                <input type="tel" placeholder="09012345678" id="phone" name="phone" class="form-input" value="{{ old('phone', $contact->phone) }}">
            </div>

            <div class="form-group">
                <label for="email" class="form-label">
                    メールアドレス<span class="required">*</span>
                </label>
                <div class="error-text" id="email-error">{{ $errors->first('email') }}</div>
                <input type="email" placeholder="yamadatarou@example.com" id="email" name="email" class="form-input" value="{{ old('email', $contact->email) }}" required>
            </div>

            <div class="form-group">
                <label for="message" class="form-label">
                    お問い合わせ内容をご記入ください<span class="required">*</span>
                </label>
                <div class="error-text" id="message-error">{{ $errors->first('message') }}</div>
                <textarea id="message" name="message" class="form-textarea" rows="6" required>{{ old('message', $contact->message) }}</textarea>
            </div>

            <div class="form-submit">
                <button type="submit" class="submit-btn">更　新</button>
                <form method="GET" action="{{ route('contact.index') }}" style="display: inline;">
                    <button type="submit" class="back-btn">戻　る</button>
                </form>
            </div>
        </form>
    </div>
</section>
@endif
@endsection
