<?php

namespace Tests\Feature\Feed;

use App\Models\User;
use App\Models\Post;
use App\Models\Subscription;
use App\Models\ContentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeedControllerTest extends TestCase
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

    public function testFeedShowsPostsFromSubscriptions()
    {
        $userA = $this->makeUser()->create();
        $userB = $this->makeUser()->create();

        Subscription::create([
            'subscriber_id' => $userA->id,
            'target_id' => $userB->id,
        ]);

        $post = $this->makePost()->create([
            'author_id' => $userB->id,
            'content_type_id' => 1,
            'content' => 'Hello',
        ]);

        $this->actingAs($userA);

        $response = $this->get('/feed');

        $response->assertStatus(200);
        $response->assertViewHas('posts');

        $posts = $response->viewData('posts');
        $this->assertCount(1, $posts);
        $this->assertEquals('Hello', $posts[0]->content);
    }

    public function testFeedDoesNotShowPostsFromNonSubscriptions()
    {
        $userA = $this->makeUser()->create();
        $userC = $this->makeUser()->create();

        $post = $this->makePost()->create([
            'author_id' => $userC->id,
            'content_type_id' => 1,
        ]);

        $this->actingAs($userA);

        $response = $this->get('/feed');

        $posts = $response->viewData('posts');
        $this->assertCount(0, $posts);
    }

    public function testFeedIsSortedByCreatedAtDesc()
    {
        $userA = $this->makeUser()->create();
        $userB = $this->makeUser()->create();

        Subscription::create([
            'subscriber_id' => $userA->id,
            'target_id' => $userB->id,
        ]);

        $old = $this->makePost()->create([
            'author_id' => $userB->id,
            'created_at' => now()->subDay(),
        ]);

        $new = $this->makePost()->create([
            'author_id' => $userB->id,
            'created_at' => now(),
        ]);

        $this->actingAs($userA);

        $response = $this->get('/feed');

        $posts = $response->viewData('posts');

        $this->assertEquals($new->id, $posts[0]->id);
        $this->assertEquals($old->id, $posts[1]->id);
    }

    public function testFeedFiltersByContentType()
    {
        $userA = $this->makeUser()->create();
        $userB = $this->makeUser()->create();

        Subscription::create([
            'subscriber_id' => $userA->id,
            'target_id' => $userB->id,
        ]);

        $this->makePost()->create([
            'author_id' => $userB->id,
            'content_type_id' => 1,
        ]);

        $photo = $this->makePost()->create([
            'author_id' => $userB->id,
            'content_type_id' => 3,
        ]);

        $this->actingAs($userA);

        $response = $this->get('/feed?type=photo');

        $posts = $response->viewData('posts');

        $this->assertCount(1, $posts);
        $this->assertEquals($photo->id, $posts[0]->id);
    }

    public function testFeedIsEmptyIfNoPosts()
    {
        $userA = $this->makeUser()->create();
        $userB = $this->makeUser()->create();

        Subscription::create([
            'subscriber_id' => $userA->id,
            'target_id' => $userB->id,
        ]);

        $this->actingAs($userA);

        $response = $this->get('/feed');

        $posts = $response->viewData('posts');
        $this->assertCount(0, $posts);
    }

    public function testFeedIsEmptyIfNoSubscriptions()
    {
        $userA = $this->makeUser()->create();

        $this->actingAs($userA);

        $response = $this->get('/feed');

        $posts = $response->viewData('posts');
        $this->assertCount(0, $posts);
    }

    public function testGuestCannotAccessFeed()
    {
        $response = $this->get('/feed');

        $response->assertRedirect('/login');
    }
}
