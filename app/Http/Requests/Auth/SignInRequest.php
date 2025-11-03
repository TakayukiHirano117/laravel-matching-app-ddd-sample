<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class SignInRequest extends FormRequest
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
   * @return array<string, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'email' => 'required|email',
      'password' => 'required|string',
    ];
  }

  public function messages(): array
  {
    return [
      'email.required' => 'メールアドレスは必須です。',
      'email.email' => 'メールアドレスは有効なメールアドレスである必要があります。',
      'password.required' => 'パスワードは必須です。',
    ];
  }

  public function attributes(): array
  {
    return [
      'email' => 'メールアドレス',
      'password' => 'パスワード',
    ];
  }
}

