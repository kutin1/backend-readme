<?php

namespace Database\Factories\Test;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'login' => $this->faker->unique()->userName(),
            'password' => Hash::make('password'),

            // Фейковый аватар, не пишем в storage
            'avatar' => UploadedFile::fake()
                ->image('avatar.jpg')
                ->hashName('avatars'),

            'registered_at' => now()->subDays($this->faker->numberBetween(1, 365)),
        ];
    }
}
