<?php

namespace Tests\Feature\Thumbnail;

use App\Models\User;
use App\Models\ContentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ThumbnailIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        ContentType::unguard();

        ContentType::create(['id' => 1, 'name' => 'text', 'icon' => 'text']);
        ContentType::create(['id' => 2, 'name' => 'quote', 'icon' => 'quote']);
        ContentType::create(['id' => 3, 'name' => 'photo', 'icon' => 'photo']);
        ContentType::create(['id' => 4, 'name' => 'video', 'icon' => 'video']);
        ContentType::create(['id' => 5, 'name' => 'link', 'icon' => 'link']);
    }

    public function testLinkPostGeneratesPreview()
    {
        Storage::fake('public');

        Http::fake([
            '*' => Http::response('IMAGE_DATA', 200),
        ]);

        config(['services.thumbnail.key' => 'TEST_KEY']);

        $user = $this->makeUser()->create();
        $this->actingAs($user);

        $response = $this->post('/posts', [
            'content_type_id' => 5,
            'link' => 'https://example.com',
            'title' => 'Example',
        ]);

        $response->assertRedirect();

        // Проверяем, что файл появился
        $files = Storage::disk('public')->allFiles('previews');

        $this->assertNotEmpty($files);
    }

    public function testLinkPostUsesDefaultThumbnailWhenApiFails()
    {
        Storage::fake('public');

        Http::fake([
            '*' => Http::response(null, 500),
        ]);

        config(['services.thumbnail.key' => 'TEST_KEY']);

        $user = $this->makeUser()->create();
        $this->actingAs($user);

        $response = $this->post('/posts', [
            'content_type_id' => 5,
            'link' => 'https://example.com',
            'title' => 'Example',
        ]);

        $response->assertRedirect();

        // Проверяем, что previews пусто
        $this->assertCount(0, Storage::disk('public')->allFiles('previews'));
    }
}
