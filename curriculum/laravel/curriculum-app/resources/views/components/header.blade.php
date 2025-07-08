{{-- メインページ（home）の場合のみアラートを表示 --}}
@php
    $current_route = request()->route()->getName();
@endphp

@if($current_route === 'home')
<div class="alert">
    <a href="javascript:void(0);">新型コロナウイルスに対する取り組みの最新情報をご案内</a>
</div>
@endif

<header class="header {{ $current_route !== 'home' ? 'scrolled contact-header' : '' }}">
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('img/logo.png') }}" alt="CafeCafe Logo" class="logo">
                </a>
            </div>
            <div class="nav-menu">
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="{{ route('home') }}#locations" class="nav-link">はじめに</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('home') }}#experiences" class="nav-link">体験</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('contact.index') }}" class="nav-link">お問い合わせ</a>
                    </li>
                </ul>
            </div>
            <div class="nav-auth">
                <a href="javaScript:void(0);" class="auth-link signin-btn">サインイン</a>
            </div>
            <div class="hamburger">
                <img src="{{ asset('img/menu.png') }}" alt="mobile menu">
                <div class="hamburger-menu">
                    <ul>
                        <li><a href="javaScript:void(0);" class="ham-signin-btn">サインイン</a></li>
                        <li><a href="{{ route('home') }}#locations" class="ham-nav-link">はじめに</a></li>
                        <li><a href="{{ route('home') }}#experiences" class="ham-nav-link">体験</a></li>
                        <li><a href="{{ route('contact.index') }}" class="ham-nav-link">お問い合わせ</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>

<!-- ログインモーダル -->
<div class="login-modal" id="loginModal">
    <div class="login-modal-content">
        <div class="login-header">
            <h2>サインイン</h2>
            <button class="login-close" id="loginClose">&times;</button>
        </div>
        <form class="login-form">
            <div class="login-field">
                <label for="loginEmail">メールアドレス</label>
                <input type="email" id="loginEmail" name="email" required>
            </div>
            <div class="login-field">
                <label for="loginPassword">パスワード</label>
                <input type="password" id="loginPassword" name="password" required>
            </div>
            <button type="submit" class="login-submit">サインイン</button>
            <div class="login-divider">
                <span>または</span>
            </div>
            <button type="button" class="social-login google-login">
                <img src="{{ asset('img/google.png') }}" alt="Google">
                Googleでサインイン
            </button>
            <button type="button" class="social-login facebook-login">
                <img src="{{ asset('img/fb.png') }}" alt="Facebook">
                Facebookでサインイン
            </button>
            <button type="button" class="social-login apple-login">
                <img src="{{ asset('img/apple.png') }}" alt="Apple">
                Appleでサインイン
            </button>
            <button type="button" class="social-login twitter-login">
                <img src="{{ asset('img/twitter.png') }}" alt="Twitter">
                Twitterでサインイン
            </button>
        </form>
    </div>
</div>
