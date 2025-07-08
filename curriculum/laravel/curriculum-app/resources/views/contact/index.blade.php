@extends('layouts.app')

@section('title', 'お問い合わせ - CafeCafe')

@section('content')
<section class="contact-section">
    <div class="contact-form-container">
        <h1 class="contact-title">お問い合わせ</h1>
        
        @if(!session('success') || !session('form_submitted'))
            <h2 class="form-subtitle">下記の項目をご記入の上送信ボタンを押してください</h2>
        @endif
        
        @if(session('success') && session('form_submitted'))
            <div class="success-message">
                @if(str_contains(session('success'), '更新'))
                    <p>お問い合わせ内容の更新が完了しました。</p>
                    <p>更新頂いた件につきましては、当社より折り返しご連絡を差し上げます。</p>
                @else
                    <p>お問い合わせありがとうございます。</p>
                    <p>送信頂いた件につきましては、当社より折り返しご連絡を差し上げます。</p>
                @endif
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

    <!-- 問い合わせ一覧は常に表示 -->
    @if(isset($contacts) && $contacts->count() > 0)
    <div class="contact-list-container">
        <h2 class="list-title">お問い合わせ一覧</h2>
        <div class="table-responsive">
            <table class="contact-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>氏名</th>
                        <th>フリガナ</th>
                        <th>電話番号</th>
                        <th>メールアドレス</th>
                        <th>お問い合わせ内容</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contacts as $contact)
                    <tr>
                        <td>{{ $contact->id }}</td>
                        <td>{{ $contact->name }}</td>
                        <td>{{ $contact->furigana }}</td>
                        <td>{{ $contact->phone ?? '-' }}</td>
                        <td>{{ $contact->email }}</td>
                        <td class="message-cell">{{ mb_strlen($contact->message) > 50 ? mb_substr($contact->message, 0, 50) . '...' : $contact->message }}</td>
                        <td class="action-buttons">
                            <a href="{{ route('contact.edit', $contact->id) }}" class="edit-btn">編集</a>
                            <form method="POST" action="{{ route('contact.destroy', $contact->id) }}" style="display: inline;" onsubmit="return confirm('本当に削除しますか？')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn">削除</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="contact-list-container">
        <h2 class="list-title">お問い合わせ一覧</h2>
        <p style="text-align: center; color: #666; padding: 20px;">まだお問い合わせがありません。</p>
    </div>
    @endif
</section>
@endsection
