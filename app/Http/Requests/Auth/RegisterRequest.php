<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:users,email',
            'login' => 'required|string|min:3|max:50|unique:users,login',

            'password' => 'required|string|min:6',
            'password-repeat' => 'required|same:password',

            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Введите email.',
            'email.email' => 'Укажите корректный email.',
            'email.unique' => 'Этот email уже используется.',

            'login.required' => 'Введите логин.',
            'login.min' => 'Логин должен содержать минимум 3 символа.',
            'login.max' => 'Логин не должен превышать 50 символов.',
            'login.unique' => 'Этот логин уже занят.',

            'password.required' => 'Введите пароль.',
            'password.min' => 'Пароль должен быть не короче 6 символов.',

            'password-repeat.required' => 'Повторите пароль.',
            'password-repeat.same' => 'Пароли должны совпадать.',

            'avatar.image' => 'Файл должен быть изображением.',
            'avatar.mimes' => 'Допустимые форматы: jpeg, png, jpg, gif.',
        ];
    }
}
