<?php
session_start(); // セッション開始

// 必要なクラスファイルをインクルード
require_once '../includes/ContactValidator.php';
require_once '../includes/ContactFormHandler.php';

$title = 'お問い合わせ - CafeCafe';

// フォームハンドラーを初期化
$formHandler = new ContactFormHandler();

// POSTリクエスト処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formHandler->handleRequest($_POST);
    $formHandler->redirect(); // PRGパターン
}

// メッセージとフォームデータを取得
$messages = $formHandler->getMessages();
$message_sent = $messages['message_sent'];
$field_errors = $messages['field_errors'];
$form_data = $formHandler->getFormData();

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
                    <div class="contact-info">
                        <p>送信頂いた件につきましては、当社より折り返しご連絡を差し上げます。</p>
                        <p>なお、ご連絡までに、お時間を頂く場合もございますので予めご了承ください。</p>
                        <p class="required-note"><span class="required">*</span>は必須項目となります。</p>
                    </div>

                    <form class="contact-form" method="POST" action="contact.php" novalidate>
                        <div class="form-group">
                            <label for="name" class="form-label">
                                氏名<span class="required">*</span>
                            </label>
                            <div class="error-text" id="name-error"><?php echo htmlspecialchars($field_errors['name'] ?? ''); ?></div>
                            <input type="text" placeholder="山田太郎" id="name" name="name" class="form-input" value="<?php echo htmlspecialchars($form_data['name'] ?? ''); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="furigana" class="form-label">
                                フリガナ<span class="required">*</span>
                            </label>
                            <div class="error-text" id="furigana-error"><?php echo htmlspecialchars($field_errors['furigana'] ?? ''); ?></div>
                            <input type="text" placeholder="ヤマダタロウ" id="furigana" name="furigana" class="form-input" value="<?php echo htmlspecialchars($form_data['furigana'] ?? ''); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="phone" class="form-label">
                                電話番号
                            </label>
                            <div class="error-text" id="phone-error"><?php echo htmlspecialchars($field_errors['phone'] ?? ''); ?></div>
                            <input type="tel" placeholder="09012345678" id="phone" name="phone" class="form-input" value="<?php echo htmlspecialchars($form_data['phone'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">
                                メールアドレス<span class="required">*</span>
                            </label>
                            <div class="error-text" id="email-error"><?php echo htmlspecialchars($field_errors['email'] ?? ''); ?></div>
                            <input type="email" placeholder="yamadatarou@example.com" id="email" name="email" class="form-input" value="<?php echo htmlspecialchars($form_data['email'] ?? ''); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="message" class="form-label">
                                お問い合わせ内容をご記入ください<span class="required">*</span>
                            </label>
                            <div class="error-text" id="message-error"><?php echo htmlspecialchars($field_errors['message'] ?? ''); ?></div>
                            <textarea id="message" name="message" class="form-textarea" rows="6" required><?php echo htmlspecialchars($form_data['message'] ?? ''); ?></textarea>
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
