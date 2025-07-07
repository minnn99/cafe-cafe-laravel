@extends('layouts.app')

@section('title', '内容確認 - CafeCafe')

@section('content')
<section class="contact-section">
    <div class="contact-form-container">
        <h1 class="contact-title">お問い合わせ内容確認</h1>
        <h2 class="confirm-subtitle">下記の内容をご確認の上送信ボタンを押してください</br>内容を訂正する場合は戻るを押してください。</h2>

        <div class="confirm-content">
            <div class="confirm-item">
                <label class="confirm-label">氏名</label>
                <div class="confirm-value">{{ $data['name'] }}</div>
            </div>
            
            <div class="confirm-item">
                <label class="confirm-label">フリガナ</label>
                <div class="confirm-value">{{ $data['furigana'] }}</div>
            </div>
            
            @if(!empty($data['phone']))
            <div class="confirm-item">
                <label class="confirm-label">電話番号</label>
                <div class="confirm-value">{{ $data['phone'] }}</div>
            </div>
            @endif
            
            <div class="confirm-item">
                <label class="confirm-label">メールアドレス</label>
                <div class="confirm-value">{{ $data['email'] }}</div>
            </div>
            
            <div class="confirm-item">
                <label class="confirm-label">お問い合わせ内容</label>
                <div class="confirm-value message-content">{!! nl2br(e($data['message'])) !!}</div>
            </div>
        </div>
        
        <div class="confirm-buttons">
          <form method="POST" action="{{ route('contact.send') }}">
            @csrf
            <button type="submit" class="send-btn">送信</button>
          </form>

          <form method="POST" action="{{ route('contact.back') }}">
              @csrf
              <button type="submit" class="back-btn">戻る</button>
          </form>
        </div>
    </div>
</section>
@endsection
