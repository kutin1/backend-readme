<?php

namespace Tests\Feature\Profile;

use App\Models\User;
use App\Models\Post;
use App\Models\Subscription;
use App\Models\ContentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
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


    public function testUserCanSubscribe()
    {
        Queue::fake();

        $userA = $this->makeUser()->create();
        $userB = $this->makeUser()->create();

        $this->actingAs($userA);

        $response = $this->post(route('profile.subscribe', $userB));

        $response->assertRedirect();

        $this->assertDatabaseHas('subscriptions', [
            'subscriber_id' => $userA->id,
            'target_id' => $userB->id,
        ]);
    }

    public function testUserCannotSubscribeTwice()
    {
        Queue::fake();

        $userA = $this->makeUser()->create();
        $userB = $this->makeUser()->create();

        Subscription::create([
            'subscriber_id' => $userA->id,
            'target_id' => $userB->id,
        ]);

        $this->actingAs($userA);

        $this->post(route('profile.subscribe', $userB));
        $this->post(route('profile.subscribe', $userB));

        $this->assertEquals(
            1,
            Subscription::where('subscriber_id', $userA->id)
                ->where('target_id', $userB->id)
                ->count()
        );

        Queue::assertNothingPushed();
    }

    public function testUserCannotSubscribeToSelf()
    {
        $userA = $this->makeUser()->create();

        $this->actingAs($userA);

        $response = $this->post(route('profile.subscribe', $userA));

        $response->assertForbidden();
    }

    public function testEmailIsSentOnlyOnNewSubscription()
    {
        Queue::fake();

        $userA = $this->makeUser()->create();
        $userB = $this->makeUser()->create();

        $this->actingAs($userA);

        // первая подписка → письмо должно быть отправлено
        $this->post(route('profile.subscribe', $userB));

        Queue::assertPushed(\App\Jobs\SendEmailJob::class);

        // повторная подписка → письмо НЕ отправляется
        Queue::fake();
        $this->post(route('profile.subscribe', $userB));

        Queue::assertNothingPushed();
    }

    public function testUserCanUnsubscribe()
    {
        $userA = $this->makeUser()->create();
        $userB = $this->makeUser()->create();

        Subscription::create([
            'subscriber_id' => $userA->id,
            'target_id' => $userB->id,
        ]);

        $this->actingAs($userA);

        $response = $this->post(route('profile.unsubscribe', $userB));

        $response->assertRedirect();

        $this->assertDatabaseMissing('subscriptions', [
            'subscriber_id' => $userA->id,
            'target_id' => $userB->id,
        ]);
    }

    public function testProfilePostsShowsOnlyUserPosts()
    {
        $userA = $this->makeUser()->create();
        $userB = $this->makeUser()->create();

        $this->makePost()->count(3)->create(['author_id' => $userA->id]);
        $this->makePost()->count(2)->create(['author_id' => $userB->id]);

        $this->actingAs($userA);

        $response = $this->get(route('profile.posts', $userA));

        $response->assertStatus(200);

        $posts = $response->viewData('posts');

        $this->assertCount(3, $posts);
        $this->assertTrue($posts->every(fn($p) => $p->author_id === $userA->id));
    }

    public function testProfilePostsShowsSubscriptionStatus()
    {
        $userA = $this->makeUser()->create();
        $userB = $this->makeUser()->create();

        Subscription::create([
            'subscriber_id' => $userA->id,
            'target_id' => $userB->id,
        ]);

        $this->actingAs($userA);

        $response = $this->get(route('profile.posts', $userB));

        $this->assertTrue($response->viewData('isSubscribed'));
    }

    public function testProfileLikesShowsOnlyLikedPosts()
    {
        $userA = $this->makeUser()->create();

        $post1 = $this->makePost()->create();
        $post2 = $this->makePost()->create();

        $post1->likes()->create(['user_id' => $userA->id]);

        $this->actingAs($userA);

        $response = $this->get(route('profile.likes', $userA));

        $liked = $response->viewData('likedPosts');

        $this->assertCount(1, $liked);
        $this->assertEquals($post1->id, $liked[0]->id);
    }

    public function testProfileSubscriptionsShowsTargets()
    {
        $userA = $this->makeUser()->create();
        $userB = $this->makeUser()->create();
        $userC = $this->makeUser()->create();

        Subscription::create(['subscriber_id' => $userA->id, 'target_id' => $userB->id]);
        Subscription::create(['subscriber_id' => $userA->id, 'target_id' => $userC->id]);

        $this->actingAs($userA);

        $response = $this->get(route('profile.subscriptions', $userA));

        $subs = $response->viewData('subscriptions');

        $this->assertCount(2, $subs);
        $this->assertEqualsCanonicalizing(
            [$userB->id, $userC->id],
            $subs->pluck('id')->toArray()
        );
    }

    public function testGuestCannotAccessProfile()
    {
        $userA = $this->makeUser()->create();

        $response = $this->get(route('profile.posts', $userA));

        $response->assertRedirect('/login');
    }
}
