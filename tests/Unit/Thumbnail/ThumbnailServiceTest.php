<?php

namespace Tests\Unit\Thumbnail;

use App\Services\Thumbnail\ThumbnailService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ThumbnailServiceTest extends TestCase
{
    public function testReturnsDefaultThumbnailIfApiKeyMissing()
    {
        Storage::fake('public');

        config(['services.thumbnail.key' => null]);

        Http::fake(); // запрос всё равно уйдёт, но неважно

        $service = new ThumbnailService();

        $result = $service->makePreview('https://example.com');

        $this->assertEquals('/img/default-thumbnail.jpg', $result);
    }

    public function testReturnsDefaultThumbnailIfApiFails()
    {
        Storage::fake('public');

        config(['services.thumbnail.key' => 'TEST_KEY']);

        Http::fake([
            '*' => Http::response(null, 500),
        ]);

        $service = new ThumbnailService();

        $result = $service->makePreview('https://example.com');

        $this->assertEquals('/img/default-thumbnail.jpg', $result);
    }

    public function testSavesPreviewOnSuccessfulApiResponse()
    {
        Storage::fake('public');

        config(['services.thumbnail.key' => 'TEST_KEY']);

        Http::fake([
            '*' => Http::response('IMAGE_BINARY', 200),
        ]);

        $service = new ThumbnailService();

        $path = $service->makePreview('https://example.com');

        // Путь должен быть previews/...
        $this->assertStringStartsWith('previews/', $path);

        // Файл должен существовать
        Storage::disk('public')->assertExists($path);

        // Содержимое должно совпадать
        $this->assertEquals('IMAGE_BINARY', Storage::disk('public')->get($path));
    }

    // public function testCorrectRequestIsSent()
    // {
    //     Storage::fake('public');

    //     config(['services.thumbnail.key' => 'TEST_KEY']);

    //     Http::fake();

    //     $service = new ThumbnailService();

    //     $service->makePreview('https://example.com');

    //     Http::assertSent(function ($request) {
    //         return
    //             $request->url() === 'https://api.thumbnail.ws/api/TEST_KEY/thumbnail/get'
    //             && $request['url'] === 'https://example.com'
    //             && $request['width'] === 640;
    //     });
    // }
}
