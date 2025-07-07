<form action="{{ route('contact.store') }}" method="POST" class="contact-form" id="contactForm">
    @csrf
    
    <div class="form-group">
        <label for="name">氏名<span class="required">*</span></label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" maxlength="10" required>
        @error('name')
            <span class="error-message show">{{ $message }}</span>
        @enderror
        <span class="error-message" id="name-error"></span>
    </div>

    <div class="form-group">
        <label for="furigana">フリガナ<span class="required">*</span></label>
        <input type="text" id="furigana" name="furigana" value="{{ old('furigana') }}" maxlength="10" required>
        @error('furigana')
            <span class="error-message show">{{ $message }}</span>
        @enderror
        <span class="error-message" id="furigana-error"></span>
    </div>

    <div class="form-group">
        <label for="phone">電話番号</label>
        <input type="text" id="phone" name="phone" value="{{ old('phone') }}">
        @error('phone')
            <span class="error-message show">{{ $message }}</span>
        @enderror
        <span class="error-message" id="phone-error"></span>
    </div>

    <div class="form-group">
        <label for="email">メールアドレス<span class="required">*</span></label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        @error('email')
            <span class="error-message show">{{ $message }}</span>
        @enderror
        <span class="error-message" id="email-error"></span>
    </div>

    <div class="form-group">
        <label for="message">お問い合わせ内容<span class="required">*</span></label>
        <textarea id="message" name="message" rows="8" placeholder="ご質問やご要望をお聞かせください" required>{{ old('message') }}</textarea>
        @error('message')
            <span class="error-message show">{{ $message }}</span>
        @enderror
        <span class="error-message" id="message-error"></span>
    </div>

    <button type="submit" class="btn-submit">内容を確認する</button>
</form>
