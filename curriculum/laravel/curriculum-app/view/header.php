<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'CafeCafe - あなたの好きな空間を作る'; ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="alert">
        <a href="javascript:void(0);">新型コロナウイルスに対する取り組みの最新情報をご案内</a>
    </div>
    <header class="header">
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
    <main class="main-content">
