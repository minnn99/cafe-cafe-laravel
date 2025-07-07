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
            'furigana' => 'required|max:10|regex:/^[ァ-ヶー]+$/u',
            'phone' => 'nullable|regex:/^[0-9-]+$/',
            'email' => 'required|email',
            'message' => 'required',
        ];
    }

    /**
     * カスタムエラーメッセージ
     */
    public function messages(): array
    {
        return [
            'name.required' => '氏名は必須入力です。',
            'name.max' => '氏名は10文字以内で入力してください。',
            'furigana.required' => 'フリガナは必須入力です。',
            'furigana.max' => 'フリガナは10文字以内で入力してください。',
            'furigana.regex' => 'フリガナはカタカナで入力してください。',
            'phone.regex' => '電話番号には半角数字しか入力出来ません。',
            'email.required' => 'メールアドレスは必須入力です。',
            'email.email' => 'メールアドレスは正しい形式でしか入力出来ません。',
            'message.required' => 'お問い合わせ内容は必須入力です。',
        ];
    }
}
