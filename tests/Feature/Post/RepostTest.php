<?php

namespace Tests\Feature\Post;

use App\Models\User;
use App\Models\Post;
use App\Models\Hashtag;
use App\Models\ContentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RepostTest extends TestCase
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

    public function testUserCanRepostAnotherUsersPost()
    {
        $author = $this->makeUser()->create();
        $reposter = $this->makeUser()->create();

        $original = $this->makePost()->create([
            'author_id' => $author->id,
            'content_type_id' => 1,
            'content' => 'Original text',
            'title' => 'Original title',
            'quote_author' => null,
            'image' => null,
            'video' => null,
            'link' => null,
        ]);

        $this->actingAs($reposter);

        $response = $this->post(route('post.repost', $original));

        $response->assertRedirect(route('profile.posts', $reposter->id));

        $this->assertDatabaseHas('posts', [
            'author_id' => $reposter->id,
            'original_post_id' => $original->id,
            'is_repost' => true,
        ]);
    }

    public function testRepostCopiesAllFields()
    {
        $author = $this->makeUser()->create();
        $reposter = $this->makeUser()->create();

        $original = $this->makePost()->create([
            'author_id' => $author->id,
            'content_type_id' => 1,
            'title' => 'Hello',
            'content' => 'World',
            'quote_author' => 'Someone',
            'image' => 'img/test.jpg',
            'video' => 'https://youtube.com/watch?v=abc',
            'link' => 'https://example.com',
        ]);

        $this->actingAs($reposter);

        $this->post(route('post.repost', $original));

        $repost = Post::where('is_repost', true)->first();

        $this->assertEquals('Hello', $repost->title);
        $this->assertEquals('World', $repost->content);
        $this->assertEquals('Someone', $repost->quote_author);
        $this->assertEquals('img/test.jpg', $repost->image);
        $this->assertEquals('https://youtube.com/watch?v=abc', $repost->video);
        $this->assertEquals('https://example.com', $repost->link);
    }

    public function testRepostCopiesHashtags()
    {
        $author = $this->makeUser()->create();
        $reposter = $this->makeUser()->create();

        $original = $this->makePost()->create([
            'author_id' => $author->id,
            'content_type_id' => 1,
            'content' => 'Test',
        ]);

        $tag1 = Hashtag::create(['name' => 'php']);
        $tag2 = Hashtag::create(['name' => 'laravel']);

        $original->hashtags()->sync([$tag1->id, $tag2->id]);

        $this->actingAs($reposter);

        $this->post(route('post.repost', $original));

        $repost = Post::where('is_repost', true)->first();

        $this->assertCount(2, $repost->hashtags);
        $this->assertEqualsCanonicalizing(
            [$tag1->id, $tag2->id],
            $repost->hashtags()->pluck('id')->toArray()
        );
    }
}
