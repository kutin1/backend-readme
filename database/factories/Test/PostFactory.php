<?php

namespace Database\Factories\Test;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $fakerRu = fake('ru_RU');

        $types = ['text', 'quote', 'photo', 'video', 'link'];
        $type = $this->faker->randomElement($types);

        $title = $this->faker->boolean(60)
            ? $fakerRu->realText($this->faker->numberBetween(20, 50))
            : null;

        $content = null;
        $quote_author = null;
        $image = null;
        $video = null;
        $link = null;

        switch ($type) {

            case 'text':
                $content = $fakerRu->realText($this->faker->numberBetween(120, 300));
                break;

            case 'quote':
                $content = $fakerRu->realText($this->faker->numberBetween(60, 140));
                $quote_author = $fakerRu->name();
                break;

            case 'photo':
                // Фейковое изображение, не пишем в storage
                $image = UploadedFile::fake()->image('photo.jpg')->hashName('posts');
                break;

            case 'video':
                // Фейковый ID
                $videoId = Str::random(11);
                $video = "https://www.youtube.com/watch?v={$videoId}";

                // Фейковое превью
                $image = UploadedFile::fake()->image('preview.jpg')->hashName('previews');
                break;

            case 'link':
                $links = [
                    'https://laravel.com',
                    'https://symfony.com',
                    'https://github.com',
                    'https://stackoverflow.com',
                    'https://php.net',
                ];
                $link = $this->faker->randomElement($links);
                break;
        }

        return [
            'author_id' => User::factory(),

            'content_type_id' => match ($type) {
                'text'  => 1,
                'quote' => 2,
                'photo' => 3,
                'video' => 4,
                'link'  => 5,
            },

            'title' => $title,
            'content' => $content,
            'quote_author' => $quote_author,
            'image' => $image,
            'video' => $video,
            'link' => $link,

            'views' => $this->faker->numberBetween(0, 5000),
        ];
    }
}
