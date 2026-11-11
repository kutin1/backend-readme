<?php

namespace Tests\Feature\Post;

use App\Models\Post;
use App\Models\User;
use App\Models\Hashtag;
use App\Models\ContentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttachTagsTest extends TestCase
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


    public function testTagsAreCreatedAndAttached()
    {
        $user = $this->makeUser()->create();
        $this->actingAs($user);

        $response = $this->post(route('posts.store'), [
            'content_type_id' => 1,
            'content' => 'Hello',
            'tags' => '#php #laravel',
        ]);

        $response->assertRedirect(route('feed'));

        $post = Post::first();

        $this->assertCount(2, $post->hashtags);

        $this->assertDatabaseHas('hashtags', ['name' => 'php']);
        $this->assertDatabaseHas('hashtags', ['name' => 'laravel']);
    }

    public function testTagsAreNormalized()
    {
        $user = $this->makeUser()->create();
        $this->actingAs($user);

        $this->post(route('posts.store'), [
            'content_type_id' => 1,
            'content' => 'Hello',
            'tags' => '#PHP   #Laravel  #TeSt',
        ]);

        $post = Post::first();

        $this->assertEqualsCanonicalizing(
            ['php', 'laravel', 'test'],
            $post->hashtags()->pluck('name')->toArray()
        );
    }

    public function testDuplicateTagsAreNotCreated()
    {
        $user = $this->makeUser()->create();
        $this->actingAs($user);

        // Уже есть тег php
        Hashtag::create(['name' => 'php']);

        $this->post(route('posts.store'), [
            'content_type_id' => 1,
            'content' => 'Hello',
            'tags' => '#php #php #PHP',
        ]);

        $post = Post::first();

        // Должен быть только один тег
        $this->assertCount(1, $post->hashtags);

        $this->assertEquals('php', $post->hashtags()->first()->name);
    }

    public function testEmptyTagsAreIgnored()
    {
        $user = $this->makeUser()->create();
        $this->actingAs($user);

        $this->post(route('posts.store'), [
            'content_type_id' => 1,
            'content' => 'Hello',
            'tags' => '   #php     #   #laravel   ',
        ]);

        $post = Post::first();

        $this->assertCount(2, $post->hashtags);
    }
}
