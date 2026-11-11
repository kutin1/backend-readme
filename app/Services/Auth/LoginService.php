<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginService
{
    public function login(array $data)
    {
        $user = User::where('email', $data['email'])->first();

        // Пользователь не найден → ошибка на email
        if (! $user) {
            return $this->redirectWithError($data, [
                'email' => 'Пользователь с таким email не существует',
            ]);
        }

        // Пароль неверный → ошибка на password
        if (! Hash::check($data['password'], $user->password)) {
            return $this->redirectWithError($data, [
                'password' => 'Вы ввели неверный пароль',
            ]);
        }

        Auth::login($user);

        return redirect()->route('feed');
    }

    private function redirectWithError(array $data, array $errors)
    {
        if (($data['from'] ?? null) === 'main') {
            return redirect()->route('main')
                ->withErrors($errors)
                ->withInput();
        }

        return redirect()->route('login')
            ->withErrors($errors)
            ->withInput();
    }
}
