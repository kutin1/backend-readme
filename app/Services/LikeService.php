<?php

namespace App\Services;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;

class LikeService
{
    public function like(User $user, int $postId): void
    {
        $post = Post::findOrFail($postId);

        Like::firstOrCreate([
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
    }
}
