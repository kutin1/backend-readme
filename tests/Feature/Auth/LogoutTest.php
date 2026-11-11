<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanLogoutSuccessfully()
    {
        // Создаём пользователя
        $user = $this->makeUser()->create();

        // Авторизуемся
        $this->actingAs($user);

        // Выполняем выход
        $response = $this->post(route('logout'));

        // Должен быть редирект на логин
        $response->assertRedirect(route('login'));

        // Пользователь должен стать гостем
        $this->assertGuest();
    }

    public function testGuestCannotAccessLogoutRoute()
    {
        // Гость пытается вызвать logout
        $response = $this->post(route('logout'));

        // Должен быть редирект на логин (middleware auth)
        $response->assertRedirect(route('login'));
    }
}
