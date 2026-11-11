<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function register(array $data): User
    {
        return User::create([
            'email' => $data['email'],
            'login' => $data['login'],
            'password' => Hash::make($data['password']),
            'avatar' => $data['avatar'],
        ]);
    }
}
