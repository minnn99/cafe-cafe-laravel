@extends('layouts.app')

@section('title', 'お問い合わせ - CafeCafe')

@section('content')
<section class="contact-section">
    <div class="contact-form-container">
        <h1 class="contact-title">お問い合わせ</h1>
        @if(!session('success'))
            <h2 class="form-subtitle">下記の項目をご記入の上送信ボタンを押してください</h2>
        @endif
        
        @if(session('success'))
            <div class="success-message">
                <p>お問い合わせありがとうございます。</p>
                <p>送信頂いた件につきましては、当社より折り返しご連絡を差し上げます。</p>
                <p>なお、ご連絡までに、お時間を頂く場合もございますので予めご了承ください。</p>
                <p><a href="{{ route('home') }}" class="back-link">トップへ戻る</a></p>
            </div>
        @else
            <div class="contact-info">
                <p>送信頂いた件につきましては、当社より折り返しご連絡を差し上げます。</p>
                <p>なお、ご連絡までに、お時間を頂く場合もございますので予めご了承ください。</p>
                <p class="required-note"><span class="required">*</span>は必須項目となります。</p>
            </div>

            <form class="contact-form" method="POST" action="{{ route('contact.store') }}" novalidate>
                @csrf
                <div class="form-group">
                    <label for="name" class="form-label">
                        氏名<span class="required">*</span>
                    </label>
                    <div class="error-text" id="name-error">{{ $errors->first('name') }}</div>
                    <input type="text" placeholder="山田太郎" id="name" name="name" class="form-input" value="{{ old('name', session('contact_data.name')) }}" required>
                </div>

                <div class="form-group">
                    <label for="furigana" class="form-label">
                        フリガナ<span class="required">*</span>
                    </label>
                    <div class="error-text" id="furigana-error">{{ $errors->first('furigana') }}</div>
                    <input type="text" placeholder="ヤマダタロウ" id="furigana" name="furigana" class="form-input" value="{{ old('furigana', session('contact_data.furigana')) }}" required>
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">
                        電話番号
                    </label>
                    <div class="error-text" id="phone-error">{{ $errors->first('phone') }}</div>
                    <input type="tel" placeholder="09012345678" id="phone" name="phone" class="form-input" value="{{ old('phone', session('contact_data.phone')) }}">
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">
                        メールアドレス<span class="required">*</span>
                    </label>
                    <div class="error-text" id="email-error">{{ $errors->first('email') }}</div>
                    <input type="email" placeholder="yamadatarou@example.com" id="email" name="email" class="form-input" value="{{ old('email', session('contact_data.email')) }}" required>
                </div>

                <div class="form-group">
                    <label for="message" class="form-label">
                        お問い合わせ内容をご記入ください<span class="required">*</span>
                    </label>
                    <div class="error-text" id="message-error">{{ $errors->first('message') }}</div>
                    <textarea id="message" name="message" class="form-textarea" rows="6" required>{{ old('message', session('contact_data.message')) }}</textarea>
                </div>

                <div class="form-submit">
                    <button type="submit" class="submit-btn">送　信</button>
                </div>
            </form>
        @endif
    </div>
</section>
@endsection
