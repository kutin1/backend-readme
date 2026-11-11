<?php

namespace Tests\Feature\Post;

use App\Models\User;
use App\Models\ContentType;
use App\Models\Post;
use App\Models\Hashtag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Queue::fake();

        ContentType::unguard();

        ContentType::create(['id' => 1, 'name' => 'text', 'icon' => 'text']);
        ContentType::create(['id' => 2, 'name' => 'quote', 'icon' => 'quote']);
        ContentType::create(['id' => 3, 'name' => 'photo', 'icon' => 'photo']);
        ContentType::create(['id' => 4, 'name' => 'video', 'icon' => 'video']);
        ContentType::create(['id' => 5, 'name' => 'link', 'icon' => 'link']);
    }

    public function testUserCanCreateTextPost()
    {
        $user = $this->makeUser()->create();
        $this->actingAs($user);

        $response = $this->post(route('posts.store'), [
            'content_type_id' => 1,
            'content' => 'Hello world',
            'tags' => '#php #laravel',
        ]);

        $response->assertRedirect(route('feed'));

        $this->assertDatabaseHas('posts', [
            'author_id' => $user->id,
            'content' => 'Hello world',
        ]);

        $post = Post::first();
        $this->assertCount(2, $post->hashtags);
    }

    public function testUserCanCreateImagePostWithFile()
    {
        $user = $this->makeUser()->create();
        $this->actingAs($user);

        $file = UploadedFile::fake()->image('photo.jpg');

        $response = $this->post(route('posts.store'), [
            'content_type_id' => 3,
            'image_file' => $file,
            'tags' => '#cat',
        ]);

        $response->assertRedirect(route('feed'));

        $post = Post::first();

        Storage::disk('public')->assertExists($post->image);
        $this->assertEquals(1, $post->hashtags()->count());
    }

    public function testUserCanCreateImagePostWithUrl()
    {
        $user = $this->makeUser()->create();
        $this->actingAs($user);

        // Валидный URL картинки
        $url = 'https://images.unsplash.com/photo-1503023345310-bd7c1de61c7d';

        // Подделываем file_get_contents
        $this->mock(\App\Services\PostService::class, function ($mock) use ($url) {
            $mock->shouldReceive('create')->andReturn(
                $this->makePost()->create(['image' => 'posts/fake.jpg'])
            );
        });

        $response = $this->post(route('posts.store'), [
            'content_type_id' => 3,
            'image_url' => $url,
        ]);

        $response->assertRedirect(route('feed'));
    }

    public function testUserCanCreateVideoPost()
    {
        $user = $this->makeUser()->create();
        $this->actingAs($user);

        $response = $this->post(route('posts.store'), [
            'content_type_id' => 4,
            'video' => 'https://www.youtube.com/watch?v=abc123',
        ]);

        $response->assertRedirect(route('feed'));

        $post = Post::first();
        $this->assertEquals('https://www.youtube.com/watch?v=abc123', $post->video);
    }

    public function testUserCanCreateLinkPost()
    {
        $user = $this->makeUser()->create();
        $this->actingAs($user);

        // Мокаем ThumbnailService
        $this->mock(\App\Services\Thumbnail\ThumbnailServiceInterface::class, function ($mock) {
            $mock->shouldReceive('makePreview')->andReturn('previews/test.jpg');
        });

        $response = $this->post(route('posts.store'), [
            'content_type_id' => 5,
            'link' => 'https://example.com',
        ]);

        $response->assertRedirect(route('feed'));

        $post = Post::first();
        $this->assertEquals('https://example.com', $post->link);
        $this->assertEquals('previews/test.jpg', $post->image);
    }

    public function testAjaxRequestReturnsJson()
    {
        $user = $this->makeUser()->create();
        $this->actingAs($user);

        $response = $this->postJson(route('posts.store'), [
            'content_type_id' => 1,
            'content' => 'AJAX post',
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'status' => 'success',
        ]);
    }

    public function testUserCanCreateQuotePost()
    {
        $user = $this->makeUser()->create();
        $this->actingAs($user);

        $response = $this->post(route('posts.store'), [
            'content_type_id' => 2,
            'content' => 'Мудрая цитата',
            'quote_author' => 'Аристотель',
            'tags' => '#цитата #философия',
        ]);

        $response->assertRedirect(route('feed'));

        $post = Post::first();

        $this->assertEquals(2, $post->content_type_id);
        $this->assertEquals('Мудрая цитата', $post->content);
        $this->assertEquals('Аристотель', $post->quote_author);

        // Проверяем теги
        $this->assertCount(2, $post->hashtags);
        $this->assertDatabaseHas('hashtags', ['name' => 'цитата']);
        $this->assertDatabaseHas('hashtags', ['name' => 'философия']);
    }

    public function testGuestCannotCreatePost()
    {
        $response = $this->post(route('posts.store'), [
            'content_type_id' => 1,
            'content' => 'Тест',
        ]);

        $response->assertRedirect(route('login'));
    }
}
