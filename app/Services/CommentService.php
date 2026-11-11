<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

class CommentService
{
    public function create(User $author, int $postId, string $content): Comment
    {
        $post = Post::findOrFail($postId);

        return Comment::create([
            'author_id' => $author->id,
            'post_id' => $post->id,
            'content' => trim($content),
        ]);
    }
}
