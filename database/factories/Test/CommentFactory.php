<?php

namespace Database\Factories\Test;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        $fakerRu = fake('ru_RU');

        return [
            'author_id' => User::factory(),
            'post_id' => Post::factory(),
            'content' => $fakerRu->realText($this->faker->numberBetween(40, 120)),
        ];
    }
}
