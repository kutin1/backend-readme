<?php

namespace App\Services\Thumbnail;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ThumbnailService implements ThumbnailServiceInterface
{
    public function makePreview(string $url): string
    {
        $apiKey = config('services.thumbnail.key');

        // Если ключа нет — корректный fallback
        if (!$apiKey) {
            return '/img/default-thumbnail.jpg';
        }

        // Локально отключаем SSL, на проде — включаем
        $http = app()->environment('local')
            ? Http::withoutVerifying()
            : Http::withOptions(['verify' => true]);

        $response = $http->get(
            "https://api.thumbnail.ws/api/$apiKey/thumbnail/get",
            [
                'url' => $url,
                'width' => 640,
            ]
        );

        if (!$response->successful()) {
            return '/img/default-thumbnail.jpg';
        }

        $filename = 'previews/' . uniqid('preview_', true) . '.jpg';

        Storage::disk('public')->put($filename, $response->body());

        return $filename;
    }
}
