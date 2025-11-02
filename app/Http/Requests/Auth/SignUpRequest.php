<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends FormRequest
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
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:254|unique:users,email',
            'password' => 'required|string|min:8',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'ユーザー名は必須です。',
            'name.string' => 'ユーザー名は文字列である必要があります。',
            'name.max' => 'ユーザー名は50文字以下である必要があります。',
            'email.required' => 'メールアドレスは必須です。',
            'email.email' => 'メールアドレスは有効なメールアドレスである必要があります。',
            'email.max' => 'メールアドレスは254文字以下である必要があります。',
        ];
    }
    public function attributes(): array
    {
        return [
            'name' => 'ユーザー名',
            'email' => 'メールアドレス',
            'password' => 'パスワード',
        ];
    }
}
