<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $avatarUrl = "https://i.pravatar.cc/300?img=" . rand(1, 70);
        $filename = 'avatars/' . uniqid('avatar_') . '.jpg';

        try {
            $data = file_get_contents($avatarUrl);
            \Storage::disk('public')->put($filename, $data);
            $avatar = $filename;
        } catch (\Exception $e) {
            $avatar = null;
        }

        return [
            'email' => $this->faker->unique()->safeEmail(),
            'login' => $this->faker->unique()->userName(),
            'password' => Hash::make('password'),
            // 'avatar' => "https://i.pravatar.cc/300?img=" . rand(1, 70),
            'avatar' => $avatar,
            'registered_at' => now()->subDays(rand(1, 365)),
        ];
    }
}
