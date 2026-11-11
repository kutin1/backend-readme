<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Hashtag;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Services\Thumbnail\ThumbnailServiceInterface;

class PostService
{
    public function __construct(
        private readonly ThumbnailServiceInterface $thumbnailService
    ) {
    }

    /**
     * Создание поста
     */
    public function create(array $data, User $author): Post
    {
        // 1. Обработка типа контента
        $processed = $this->processContentType($data);

        // 2. Создание поста
        $post = Post::create([
            'author_id' => $author->id,
            'content_type_id' => $data['content_type_id'],
            'title' => $data['title'] ?? null,
            'content' => $processed['content'] ?? null,
            'quote_author' => $processed['quote_author'] ?? null,
            'image' => $processed['image'] ?? null,
            'video' => $processed['video'] ?? null,
            'link' => $processed['link'] ?? null,
            'views' => 0,
        ]);

        // Вызов события
        event(new \App\Events\PostCreated($post));

        // 3. Теги
        $this->attachTags($post, $data['tags'] ?? null);

        return $post;
    }

    public function repost(Post $original, User $author): Post
    {
        // 1. Создаём копию поста
        $post = Post::create([
            'author_id'        => $author->id,
            'content_type_id'  => $original->content_type_id,
            'title'            => $original->title,
            'content'          => $original->content,
            'quote_author'     => $original->quote_author,
            'image'            => $original->image,
            'video'            => $original->video,
            'link'             => $original->link,
            'views'            => 0,
            'is_repost'        => true,
            'original_post_id' => $original->id,
        ]);

        // 2. Копируем теги
        $tagIds = $original->hashtags()->pluck('id')->toArray();
        $post->hashtags()->sync($tagIds);

        return $post;
    }

    /**
     * Обработка типа контента
     */
    private function processContentType(array $data): array
    {
        return match ((int) $data['content_type_id']) {
            1 => $this->processText($data),
            2 => $this->processQuote($data),
            3 => $this->processImage($data),
            4 => $this->processVideo($data),
            5 => $this->processLink($data),
            default => throw new \InvalidArgumentException('Unknown content type'),
        };
    }

    private function processText(array $data): array
    {
        return [
            'content' => $data['content'],
        ];
    }

    private function processQuote(array $data): array
    {
        return [
            'content' => $data['content'],
            'quote_author' => $data['quote_author'],
        ];
    }

    private function processImage(array $data): array
    {
        // 1. Если есть файл — он главный
        if (!empty($data['image_file']) && $data['image_file'] instanceof UploadedFile) {
            $path = $data['image_file']->store('posts', 'public');

            return [
                'image' => $path,
            ];
        }

        // 2. Если файла нет — используем URL (он уже прошёл валидацию)
        if (!empty($data['image_url'])) {
            $contents = @file_get_contents($data['image_url']);

            // Валидация уже гарантирует, что это изображение
            $filename = 'posts/' . uniqid('img_') . '.jpg';
            Storage::disk('public')->put($filename, $contents);

            return [
                'image' => $filename,
            ];
        }

        return [];
    }


    private function processVideo(array $data): array
    {
        $videoUrl = $data['video'];

        // 1. Извлекаем ID видео
        preg_match('/(youtu\.be\/|v=|embed\/)([A-Za-z0-9_-]+)/', $videoUrl, $matches);
        $videoId = $matches[2] ?? null;

        if (!$videoId) {
            // Если это не YouTube — просто сохраняем ссылку
            return [
                'video' => $videoUrl,
            ];
        }

        // 2. Формируем URL превью
        $previewUrl = "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg";

        // 3. Пытаемся скачать превью
        $contents = @file_get_contents($previewUrl);

        if (!$contents) {
            // fallback на более низкое качество
            $previewUrl = "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg";
            $contents = @file_get_contents($previewUrl);
        }

        if ($contents) {
            // 4. Сохраняем превью в storage
            $filename = 'previews/' . uniqid('video_') . '.jpg';
            Storage::disk('public')->put($filename, $contents);

            return [
                'video' => $videoUrl,
                'image' => $filename,
            ];
        }

        // Если превью не удалось скачать
        return [
            'video' => $videoUrl,
        ];
    }

    private function processLink(array $data): array
    {
        $previewPath = $this->thumbnailService->makePreview($data['link']);

        return [
            'link' => $data['link'],
            'image' => $previewPath,
        ];
    }

    private function attachTags(Post $post, ?string $tags): void
    {
        $tagNames = array_filter(explode(' ', trim($tags ?? '')));
        $tagIds = [];

        foreach ($tagNames as $name) {
            // Нормализация
            $name = ltrim($name, '#');        // убираем решётку
            $name = mb_strtolower($name);     // приводим к нижнему регистру
            $name = trim($name);              // убираем пробелы

            if ($name === '') {
                continue;
            }

            $tag = Hashtag::firstOrCreate(['name' => $name]);
            $tagIds[] = $tag->id;
        }

        $post->hashtags()->sync($tagIds);
    }
}
