<?php

class ContactValidator
{
  private $errors = [];
  
  public function validate($data)
  {
    $this->errors = [];
    
    // 氏名の検証
    if (empty($data['name'])) {
      $this->errors['name'] = '氏名は必須入力です。';
    } elseif (mb_strlen($data['name']) > 10) {
      $this->errors['name'] = '氏名は10文字以内で入力してください。';
    }
    
    // フリガナの検証
    if (empty($data['furigana'])) {
      $this->errors['furigana'] = 'フリガナは必須入力です。';
    } elseif (mb_strlen($data['furigana']) > 10) {
      $this->errors['furigana'] = 'フリガナは10文字以内で入力してください。';
    } elseif (!preg_match('/^[ァ-ヶー]+$/u', $data['furigana'])) {
      $this->errors['furigana'] = 'フリガナはカタカナで入力してください。';
    }
    
    // 電話番号の検証（任意）
    if (!empty($data['phone']) && !preg_match('/^[0-9-]+$/', $data['phone'])) {
      $this->errors['phone'] = '電話番号には半角数字しか入力出来ません。';
    }
    
    // メールアドレスの検証
    if (empty($data['email'])) {
      $this->errors['email'] = 'メールアドレスは必須入力です。';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
      $this->errors['email'] = 'メールアドレスは正しい形式で入力してください。';
    }
    
    // お問い合わせ内容の検証
    if (empty($data['message'])) {
      $this->errors['message'] = 'お問い合わせ内容は必須入力です。';
    }
    
    return empty($this->errors);
  }
  
  public function getErrors()
  {
    return $this->errors;
  }
  
  public function hasErrors()
  {
    return !empty($this->errors);
  }
  
  public function getErrorMessage($field)
  {
    return $this->errors[$field] ?? '';
  }
}

