<?php
$title = 'お問い合わせ - CafeCafe';

// フォーム送信処理
$message_sent = false;
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $furigana = trim($_POST['furigana'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    // バリデーション
    if (empty($name) || empty($furigana) || empty($email) || empty($message)) {
        $error_message = '必須項目を全て入力してください。';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = '正しいメールアドレスを入力してください。';
    } else {
        // 実際のアプリケーションでは、ここでメール送信やデータベース保存を行う
        $message_sent = true;
    }
}

include '../view/header.php';
?>

<main class="main-content">
    <section class="contact-section">
            <div class="contact-form-container">
                <h1 class="contact-title">お問い合わせ</h1>
                <h2 class="form-subtitle">下記の項目をご記入の上送信ボタンを押してください</h2>
                
                <?php if ($message_sent): ?>
                    <div class="success-message">
                        <p>お問い合わせありがとうございます。</p>
                        <p>送信内容を確認の上、担当者より折り返しご連絡させていただきます。</p>
                        <p><a href="contact.php" class="back-link">新しいお問い合わせをする</a></p>
                    </div>
                <?php else: ?>
                    <?php if (!empty($error_message)): ?>
                        <div class="error-message">
                            <p><?php echo htmlspecialchars($error_message); ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <div class="contact-info">
                        <p>送信頂いた件につきましては、当社より折り返しご連絡を差し上げます。</p>
                        <p>なお、ご連絡までに、お時間を頂く場合もございますので予めご了承ください。</p>
                        <p class="required-note"><span class="required">*</span>は必須項目となります。</p>
                    </div>

                    <form class="contact-form" method="POST" action="contact.php">
                        <div class="form-group">
                            <label for="name" class="form-label">
                                氏名<span class="required">*</span>
                            </label>
                            <input type="text" id="name" name="name" class="form-input" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="furigana" class="form-label">
                                フリガナ<span class="required">*</span>
                            </label>
                            <input type="text" id="furigana" name="furigana" class="form-input" value="<?php echo htmlspecialchars($furigana ?? ''); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="phone" class="form-label">
                                電話番号
                            </label>
                            <input type="tel" id="phone" name="phone" class="form-input" value="<?php echo htmlspecialchars($phone ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">
                                メールアドレス<span class="required">*</span>
                            </label>
                            <input type="email" id="email" name="email" class="form-input" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="message" class="form-label">
                                お問い合わせ内容をご記入ください<span class="required">*</span>
                            </label>
                            <textarea id="message" name="message" class="form-textarea" rows="6" required><?php echo htmlspecialchars($message ?? ''); ?></textarea>
                        </div>

                        <div class="form-submit">
                            <button type="submit" class="submit-btn">送　信</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php
include '../view/footer.php';
?>
