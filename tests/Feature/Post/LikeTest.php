<?php

namespace Tests\Feature\Post;

use App\Models\User;
use App\Models\Post;
use App\Models\ContentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeTest extends TestCase
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


    public function testUserCanLikePost()
    {
        $user = $this->makeUser()->create();
        $this->actingAs($user);

        $post = $this->makePost()->create([
            'content_type_id' => 1,
        ]);

        $response = $this->post(route('post.like', $post));

        $response->assertRedirect();

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
    }

    public function testUserCannotLikeTwiceCreatesNoDuplicate()
    {
        $user = $this->makeUser()->create();
        $this->actingAs($user);

        $post = $this->makePost()->create([
            'content_type_id' => 1,
        ]);

        // Первый лайк
        $this->post(route('post.like', $post));

        // Повторный лайк — должен удалить лайк
        $this->post(route('post.like', $post));

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
    }

    public function testLikeIsRemovedOnSecondClick()
    {
        $user = $this->makeUser()->create();
        $this->actingAs($user);

        $post = $this->makePost()->create([
            'content_type_id' => 1,
        ]);

        // Лайк
        $this->post(route('post.like', $post));
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        // Дизлайк
        $this->post(route('post.like', $post));
        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
    }
}
