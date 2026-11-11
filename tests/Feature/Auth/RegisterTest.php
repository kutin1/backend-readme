<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanRegisterSuccessfully()
    {
        Storage::fake('public');

        $response = $this->post(route('register.perform'), [
            'email' => 'test@example.com',
            'login' => 'kutin',
            'password' => '123456',
            'password-repeat' => '123456',
            'avatar' => UploadedFile::fake()->image('avatar.jpg'),
        ]);

        // Успешная регистрация → редирект на логин
        $response->assertRedirect(route('login'));

        // Проверяем запись в БД
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'login' => 'kutin',
        ]);

        // Проверяем, что аватар сохранился
        $user = User::first();
        Storage::disk('public')->assertExists($user->avatar);
    }

    public function testRegistrationRequiresAllFields()
    {
        $response = $this->post(route('register.perform'), []);

        $response->assertSessionHasErrors([
            'email',
            'login',
            'password',
            'password-repeat',
        ]);
    }

    public function testEmailMustBeUnique()
    {
        $this->makeUser()->create(['email' => 'test@example.com']);

        $response = $this->post(route('register.perform'), [
            'email' => 'test@example.com',
            'login' => 'newlogin',
            'password' => '123456',
            'password-repeat' => '123456',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function testLoginMustBeUnique()
    {
        $this->makeUser()->create(['login' => 'kutin']);

        $response = $this->post(route('register.perform'), [
            'email' => 'new@example.com',
            'login' => 'kutin',
            'password' => '123456',
            'password-repeat' => '123456',
        ]);

        $response->assertSessionHasErrors(['login']);
    }

    public function testPasswordRepeatMustMatch()
    {
        $response = $this->post(route('register.perform'), [
            'email' => 'test@example.com',
            'login' => 'kutin',
            'password' => '123456',
            'password-repeat' => '654321',
        ]);

        $response->assertSessionHasErrors(['password-repeat']);
    }

    public function testAvatarMustBeImage()
    {
        $response = $this->post(route('register.perform'), [
            'email' => 'test@example.com',
            'login' => 'kutin',
            'password' => '123456',
            'password-repeat' => '123456',
            'avatar' => UploadedFile::fake()->create('file.pdf', 100),
        ]);

        $response->assertSessionHasErrors(['avatar']);
    }
}
