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
      // バリデーション成功時
      $this->setSuccessMessage();
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
}

