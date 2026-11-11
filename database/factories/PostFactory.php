<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\ContentType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    public function definition(): array
    {
        $fakerRu = fake('ru_RU');

        $types = [
            'text',
            'quote',
            'photo',
            'video',
            'link',
        ];

        $type = $this->faker->randomElement($types);

        // Заголовок — опционально для всех
        $title = $this->faker->boolean(60)
            ? $fakerRu->realText(rand(20, 50))
            : null;

        // Общие переменные
        $content = null;
        $quote_author = null;
        $image = null;
        $video = null;
        $link = null;

        // -------------------------
        //   ЛОГИКА ПО ТИПАМ
        // -------------------------

        switch ($type) {

            case 'text':
                // content обязателен
                $content = $fakerRu->realText(rand(120, 300));
                break;

            case 'quote':
                // content обязателен
                $content = $fakerRu->realText(rand(60, 140));
                // author обязателен
                $quote_author = $fakerRu->name();
                break;

            case 'photo':
                $url = "https://picsum.photos/seed/" . rand(1, 999999) . "/800/600";

                $filename = 'posts/photo_' . Str::random(20) . '.jpg';

                try {
                    $data = file_get_contents($url);
                    \Storage::disk('public')->put($filename, $data);
                    $image = $filename;
                } catch (\Exception $e) {
                    $image = null;
                }

                break;

            case 'video':

                $videos = [
                    'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                    'https://www.youtube.com/watch?v=9bZkp7q19f0',
                    'https://www.youtube.com/watch?v=3JZ_D3ELwOQ',
                    'https://www.youtube.com/watch?v=L_jWHffIx5E',
                    'https://www.youtube.com/watch?v=fLexgOxsZu0',
                    'https://www.youtube.com/watch?v=kXYiU_JCYtU',
                    'https://www.youtube.com/watch?v=2vjPBrBU-TM',
                    'https://www.youtube.com/watch?v=60ItHLz5WEA',
                    'https://www.youtube.com/watch?v=RgKAFK5djSk',
                    'https://www.youtube.com/watch?v=OPf0YbXqDm0',
                ];
                $video = $videos[array_rand($videos)];

                preg_match('/(youtu\.be\/|v=|embed\/)([A-Za-z0-9_-]+)/', $video, $matches);
                $videoId = $matches[2] ?? null;

                if ($videoId) {
                    $previewUrl = "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg";
                    $contents = @file_get_contents($previewUrl);

                    if (!$contents) {
                        $previewUrl = "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg";
                        $contents = @file_get_contents($previewUrl);
                    }

                    if ($contents) {
                        $filename = 'previews/' . uniqid('video_') . '.jpg';
                        Storage::disk('public')->put($filename, $contents);
                        $image = $filename;
                    }
                }

                break;

            case 'link':
                // link обязателен — реальные рабочие ссылки
                $links = [
                    'https://laravel.com',
                    'https://symfony.com',
                    'https://github.com',
                    'https://stackoverflow.com',
                    'https://php.net',
                    'https://habr.com',
                    'https://google.com',
                    'https://yandex.ru',
                    'https://developer.mozilla.org',
                    'https://wikipedia.org',
                ];
                $link = $links[array_rand($links)];
                break;
        }

        return [
            'author_id' => User::inRandomOrder()->first()->id,
            'content_type_id' => ContentType::where('icon', $type)->first()->id,

            'title' => $title,
            'content' => $content,
            'quote_author' => $quote_author,
            'image' => $image,
            'video' => $video,
            'link' => $link,

            'views' => rand(0, 5000),
        ];
    }
}
