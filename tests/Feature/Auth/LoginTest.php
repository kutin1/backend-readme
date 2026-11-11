<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanLoginSuccessfully()
    {
        // Создаём пользователя
        $user = $this->makeUser()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('123456'),
        ]);

        // Отправляем форму логина
        $response = $this->post(route('login.perform'), [
            'email' => 'test@example.com',
            'password' => '123456',
        ]);

        // Успешный логин → редирект на ленту
        $response->assertRedirect(route('feed'));

        // Проверяем, что пользователь авторизован
        $this->assertAuthenticatedAs($user);
    }

    public function testLoginFailsWithWrongPassword()
    {
        $this->makeUser()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('correctpass'),
        ]);

        $response = $this->post(route('login.perform'), [
            'email' => 'test@example.com',
            'password' => 'wrongpass',
        ]);

        // Ошибка → редирект обратно на логин
        $response->assertRedirect(route('login'));

        // Ошибка должна быть в сессии
        $response->assertSessionHasErrors(['password']);

        // Пользователь НЕ должен быть авторизован
        $this->assertGuest();
    }

    public function testLoginFailsWithNonexistentEmail()
    {
        $response = $this->post(route('login.perform'), [
            'email' => 'unknown@example.com',
            'password' => '123456',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function testLoginRequiresEmailAndPassword()
    {
        $response = $this->post(route('login.perform'), []);

        $response->assertSessionHasErrors(['email', 'password']);
        $this->assertGuest();
    }
}
