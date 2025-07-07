<form action="{{ route('contact.store') }}" method="POST" class="contact-form" id="contactForm">
    @csrf
    
    <div class="form-group">
        <label for="name">氏名<span class="required">*</span></label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" 
               maxlength="10" 
               pattern="[^<>\"'&]*" 
               title="HTML特殊文字（&lt; &gt; &quot; ' &amp;）は使用できません" 
               autocomplete="name"
               required>
        @error('name')
            <span class="error-message show">{{ $message }}</span>
        @enderror
        <span class="error-message" id="name-error"></span>
    </div>

    <div class="form-group">
        <label for="furigana">フリガナ<span class="required">*</span></label>
        <input type="text" id="furigana" name="furigana" value="{{ old('furigana') }}" 
               maxlength="10" 
               pattern="[ァ-ヶー]+" 
               title="カタカナのみ入力可能です" 
               autocomplete="given-name"
               required>
        @error('furigana')
            <span class="error-message show">{{ $message }}</span>
        @enderror
        <span class="error-message" id="furigana-error"></span>
    </div>

    <div class="form-group">
        <label for="phone">電話番号</label>
        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
               pattern="[0-9\-]*" 
               title="半角数字とハイフンのみ入力可能です"
               autocomplete="tel">
        @error('phone')
            <span class="error-message show">{{ $message }}</span>
        @enderror
        <span class="error-message" id="phone-error"></span>
    </div>

    <div class="form-group">
        <label for="email">メールアドレス<span class="required">*</span></label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" 
               pattern="[^<>\"'&]*@[^<>\"'&]*"
               title="有効なメールアドレスを入力してください（HTML特殊文字は使用不可）"
               autocomplete="email"
               required>
        @error('email')
            <span class="error-message show">{{ $message }}</span>
        @enderror
        <span class="error-message" id="email-error"></span>
    </div>

    <div class="form-group">
        <label for="message">お問い合わせ内容<span class="required">*</span></label>
        <textarea id="message" name="message" rows="8" 
                  maxlength="1000"
                  placeholder="ご質問やご要望をお聞かせください（HTML特殊文字は使用できません）"
                  title="1000文字以内で入力してください"
                  required>{{ old('message') }}</textarea>
        @error('message')
            <span class="error-message show">{{ $message }}</span>
        @enderror
        <span class="error-message" id="message-error"></span>
    </div>

    <button type="submit" class="btn-submit">内容を確認する</button>
</form>
