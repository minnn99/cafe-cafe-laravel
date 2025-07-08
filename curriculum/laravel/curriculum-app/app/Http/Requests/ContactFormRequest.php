<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:10',
            'kana' => 'required|max:10',
            'tel' => 'nullable|numeric',
            'email' => 'required|email',
            'body' => 'required',
        ];
    }

    /**
     * カスタムエラーメッセージ
     */
    public function messages(): array
    {
        return [
            'name.required' => '氏名は必須入力です。',
            'name.max' => '10文字以内で入力してください。',
            'kana.required' => 'フリガナは必須入力です。',
            'kana.max' => '10文字以内で入力してください。',
            'tel.numeric' => '電話番号には半角数字しか入力出来ません。',
            'email.required' => 'メールアドレスは必須入力です。',
            'email.email' => 'メールアドレスは正しい形式でしか入力出来ません。',
            'body.required' => 'お問い合わせ内容は必須入力です。',
        ];
    }

    /**
     * XSS対策として入力データをサニタイズ
     */
    public function prepareForValidation()
    {
        $this->merge([
            'name' => $this->sanitizeInput($this->name),
            'kana' => $this->sanitizeInput($this->kana),
            'tel' => $this->sanitizeInput($this->tel),
            'email' => $this->sanitizeInput($this->email),
            'body' => $this->sanitizeInput($this->body),
        ]);
    }

    /**
     * XSS対策のためのサニタイズ処理
     */
    private function sanitizeInput($input)
    {
        if (is_null($input)) {
            return null;
        }
        
        // HTML特殊文字をエスケープ
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        
        // 危険なタグを削除
        $input = strip_tags($input);
        
        // 余分な空白を削除
        $input = trim($input);
        
        return $input;
    }
}
