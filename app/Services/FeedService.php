<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class FeedService
{
    public function getFeed(User $user, string $type = 'all'): Collection
    {
        // Получаем ID всех, на кого подписан пользователь
        $targetIds = Subscription::where('subscriber_id', $user->id)
            ->pluck('target_id');

        // Получаем посты этих пользователей
        $query = Post::whereIn('author_id', $targetIds)
            ->with([
                'author',
                'hashtags',
                'originalPost',
                'originalPost.author',
                'originalPost.hashtags',
                'contentType',
            ]);

         // Фильтр по типу
        if ($type !== 'all') {
            $query->whereHas('contentType', function ($q) use ($type) {
                $q->where('icon', $type);
            });
        }
        return $query->orderByDesc('created_at')->get();
    }
}
