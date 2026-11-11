<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'from' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Введите email.',
            'email.email' => 'Укажите корректный email.',
            'password.required' => 'Введите пароль.',
        ];
    }
}
