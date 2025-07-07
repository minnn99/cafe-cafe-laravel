<?php

class ContactFormHandler
{
  private $validator;
  
  public function __construct()
  {
    $this->validator = new ContactValidator();
  }
  
  public function handleRequest($postData)
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      return false;
    }
    
    // データをクリーンアップ
    $data = $this->sanitizeData($postData);
    
    // バリデーションを実行
    if ($this->validator->validate($data)) {
      // バリデーション成功時、確認画面へ
      $_SESSION['confirm_data'] = $data;
      return true;
    } else {
      // バリデーションエラー時
      $this->setErrorMessages($data);
      return false;
    }
  }
  
  public function getFormData()
  {
    $formData = $_SESSION['form_data'] ?? [];
    unset($_SESSION['form_data']);
    return $formData;
  }
  
  public function getMessages()
  {
    $messageSent = $_SESSION['message_sent'] ?? false;
    $fieldErrors = $_SESSION['field_errors'] ?? [];
    
    // メッセージ取得後にセッションから削除
    unset($_SESSION['message_sent'], $_SESSION['field_errors']);
    
    return [
      'message_sent' => $messageSent,
      'field_errors' => $fieldErrors
    ];
  }
  
  private function sanitizeData($postData)
  {
    return [
      'name' => trim($postData['name'] ?? ''),
      'furigana' => trim($postData['furigana'] ?? ''),
      'phone' => trim($postData['phone'] ?? ''),
      'email' => trim($postData['email'] ?? ''),
      'message' => trim($postData['message'] ?? '')
    ];
  }
  
  private function setSuccessMessage()
  {
    $_SESSION['message_sent'] = true;
    // フォームデータをクリア
    unset($_SESSION['form_data']);
  }
  
  private function setErrorMessages($data)
  {
    $_SESSION['field_errors'] = $this->validator->getErrors();
    $_SESSION['form_data'] = $data;
  }
  
  public function redirect()
  {
    header('Location: contact.php');
    exit;
  }

  // 確認画面用のデータを取得
  public function getConfirmData()
  {
    return $_SESSION['confirm_data'] ?? [];
  }

  // 最終送信処理
  public function handleFinalSubmission()
  {
    $confirmData = $_SESSION['confirm_data'] ?? null;
    
    if ($confirmData) {
      // 実際の送信処理（ここではメッセージ設定のみ）
      $_SESSION['message_sent'] = true;
      
      // 確認データをクリア
      unset($_SESSION['confirm_data']);
      unset($_SESSION['form_data']);
      unset($_SESSION['field_errors']);
    }
  }

  // contact.phpに戻る際のフォームデータ復元
  public function restoreFormData()
  {
    $confirmData = $_SESSION['confirm_data'] ?? [];
    if (!empty($confirmData)) {
      $_SESSION['form_data'] = $confirmData;
    }
  }
}

