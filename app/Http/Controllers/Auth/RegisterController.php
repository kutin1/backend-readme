<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function __construct(
        protected AuthService $authService
    ) {
    }

    public function register(RegisterRequest $request)
    {
        // Валидированные данные
        $data = $request->validated();

        // Сохранение аватара (если есть)
        $avatar = null;

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar')->store('avatars', 'public');
        }

        // Добавляем путь к аватару в данные
        $data['avatar'] = $avatar;

        // Регистрируем пользователя через сервис
        $this->authService->register($data);

        // Редирект на страницу логина
        return redirect()->route('login');
    }
}
