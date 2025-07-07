<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'CafeCafe - あなたの好きな空間を作る'; ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php 
    // contactページとconfirmページ以外の場合のみアラートを表示
    $current_page = basename($_SERVER['PHP_SELF'], '.php');
    if ($current_page !== 'contact' && $current_page !== 'confirm'): 
    ?>
    <div class="alert">
        <a href="javascript:void(0);">新型コロナウイルスに対する取り組みの最新情報をご案内</a>
    </div>
    <?php endif; ?>
    
    <header class="header <?php echo ($current_page === 'contact' || $current_page === 'confirm') ? 'scrolled contact-header' : ''; ?>">
        <nav class="navbar">
            <div class="nav-container">
                <div class="nav-logo">
                    <a href="index.php">
                        <img src="img/logo.png" alt="CafeCafe Logo" class="logo">
                    </a>
                </div>
                <div class="nav-menu">
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="index.php#locations" class="nav-link">はじめに</a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php#experiences" class="nav-link">体験</a>
                        </li>
                        <li class="nav-item">
                            <a href="contact.php" class="nav-link">お問い合わせ</a>
                        </li>
                    </ul>
                </div>
                <div class="nav-auth">
                    <a href="javaScript:void(0);" class="auth-link signin-btn">サインイン</a>
                </div>
                <div class="hamburger">
                    <img src="img/menu.png" alt="mobile menu">
                    <div class="hamburger-menu">
                        <ul>
                            <li><a href="javaScript:void(0);" class="ham-signin-btn">サインイン</a></li>
                            <li><a href="index.php#locations" class="ham-nav-link">はじめに</a></li>
                            <li><a href="index.php#experiences" class="ham-nav-link">体験</a></li>
                            <li><a href="contact.php" class="ham-nav-link">お問い合わせ</a></li>
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
                <div class="login-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember">
                        <span>ログイン状態を保持する</span>
                    </label>
                    <a href="javascript:void(0);" class="forgot-password">パスワードを忘れた方</a>
                </div>
                <button type="submit" class="login-submit">サインイン</button>
                <div class="login-divider">
                    <span>または</span>
                </div>
                <button type="button" class="social-login google-login">
                    <img src="img/google.png" alt="Google">
                    Googleでサインイン
                </button>
                <button type="button" class="social-login facebook-login">
                    <img src="img/fb.png" alt="Facebook">
                    Facebookでサインイン
                </button>
                <button type="button" class="social-login apple-login">
                    <img src="img/apple.png" alt="Apple">
                    Appleでサインイン
                </button>
                <button type="button" class="social-login twitter-login">
                    <img src="img/twitter.png" alt="Twitter">
                    Twitterでサインイン
                </button>
            </form>
        </div>
    </div>

    <main class="main-content">
