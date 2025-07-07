<?php
session_start();

// 必要なファイルをインクルード
require_once '../includes/ContactValidator.php';
require_once '../includes/ContactFormHandler.php';

$title = 'お問い合わせ内容確認 - CafeCafe';

// フォームハンドラーを初期化
$formHandler = new ContactFormHandler();

// POSTリクエスト処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'send') {
            // 最終送信処理
            $formHandler->handleFinalSubmission();
            header('Location: contact.php');
            exit;
        } elseif ($_POST['action'] === 'back') {
            // 戻る処理 - フォームデータを復元
            $formHandler->restoreFormData();
            header('Location: contact.php');
            exit;
        }
    }
}

// セッションから確認データを取得
$confirmData = $formHandler->getConfirmData();
if (empty($confirmData)) {
    // 確認データがない場合はcontact.phpに戻る
    header('Location: contact.php');
    exit;
}

include '../view/header.php';
?>

<main class="main-content">
    <section class="contact-section">
        <div class="contact-form-container">
            <h1 class="contact-title">お問い合わせ内容確認</h1>
            <h2 class="confirm-subtitle">下記の内容をご確認の上送信ボタンを押してください</br>内容を訂正する場合は戻るを押してください。</h2>

            <div class="confirm-content">
                <div class="confirm-item">
                    <label class="confirm-label">氏名</label>
                    <div class="confirm-value"><?php echo htmlspecialchars($confirmData['name']); ?></div>
                </div>
                
                <div class="confirm-item">
                    <label class="confirm-label">フリガナ</label>
                    <div class="confirm-value"><?php echo htmlspecialchars($confirmData['furigana']); ?></div>
                </div>
                
                <?php if (!empty($confirmData['phone'])): ?>
                <div class="confirm-item">
                    <label class="confirm-label">電話番号</label>
                    <div class="confirm-value"><?php echo htmlspecialchars($confirmData['phone']); ?></div>
                </div>
                <?php endif; ?>
                
                <div class="confirm-item">
                    <label class="confirm-label">メールアドレス</label>
                    <div class="confirm-value"><?php echo htmlspecialchars($confirmData['email']); ?></div>
                </div>
                
                <div class="confirm-item">
                    <label class="confirm-label">お問い合わせ内容</label>
                    <div class="confirm-value message-content"><?php echo nl2br(htmlspecialchars($confirmData['message'])); ?></div>
                </div>
            </div>
            
            <div class="confirm-buttons">
              <form method="POST" action="confirm.php">
                <input type="hidden" name="action" value="send">
                <button type="submit" class="send-btn">送信</button>
              </form>

              <form method="POST" action="confirm.php">
                  <input type="hidden" name="action" value="back">
                  <button type="submit" class="back-btn">戻る</button>
              </form>
            </div>
        </div>
    </section>
</main>

<?php
include '../view/footer.php';
?>
