<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $fakerRu = fake('ru_RU');

        return [
            'sender_id' => User::inRandomOrder()->first()->id,
            'receiver_id' => User::inRandomOrder()->first()->id,
            'content' => $fakerRu->realText(rand(30, 100)),
        ];
    }
}
