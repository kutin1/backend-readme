<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class ProtectedRoutesTest extends TestCase
{
    use RefreshDatabase;

    private array $protectedRoutes = [
        'feed',
        'messages.index',
        'popular.index',
        'post.create',
    ];

    public function testGuestCannotAccessProtectedRoutes()
    {
        foreach ($this->protectedRoutes as $route) {
            $response = $this->get(route($route));

            // Гость должен быть редиректнут на логин
            $response->assertRedirect(route('login'));
        }
    }

    public function testAuthenticatedUserCanAccessProtectedRoutes()
    {
        $user = $this->makeUser()->create();

        foreach ($this->protectedRoutes as $route) {
            $response = $this->actingAs($user)->get(route($route));

            // Авторизованный пользователь должен получить 200
            $response->assertStatus(200);
        }
    }

    public function testGuestCannotAccessMessagesShowRoute()
    {
        $user = $this->makeUser()->create();

        $response = $this->get(route('messages.show', $user));

        $response->assertRedirect(route('login'));
    }

    public function testAuthenticatedUserCanAccessMessagesShowRoute()
    {
        $auth = $this->makeUser()->create();
        $other = $this->makeUser()->create();

        $response = $this->actingAs($auth)->get(route('messages.show', $other));

        $response->assertStatus(200);
    }
}
