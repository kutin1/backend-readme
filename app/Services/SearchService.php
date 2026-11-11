<?php

namespace App\Services;

use App\Models\Hashtag;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Support\Collection;

class SearchService
{
    public function search(string $query): array
    {
        $query = trim($query);

        // Очистка запроса от мусора
        $clean = preg_replace('/[^\p{L}\p{N}\s#]/u', '', $query);

        // ============================
        // 1. Поиск по тегам (#tag / tag)
        // ============================

        $tagName = ltrim($clean, '#');

        $tag = Hashtag::where('name', $tagName)->first();

        $tagPosts = $tag
            ? $tag->posts()
                ->with(['author', 'hashtags'])
                ->orderByDesc('created_at')
                ->get()
            : collect();


        // ============================
        // 2. Поиск по пользователям
        // ============================

        $users = User::where('login', 'LIKE', "%{$clean}%")
            ->orderBy('login')
            ->get();


        // ============================
        // 3. Fulltext поиск по постам
        // ============================

        $booleanQuery = '+' . implode(' +', explode(' ', $clean)) . '*';

        $posts = Post::whereRaw(
            "MATCH(title, content) AGAINST(? IN BOOLEAN MODE)",
            [$booleanQuery]
        )
        ->with(['author', 'hashtags'])
        ->orderByDesc('created_at')
        ->get();


        // ============================
        // 4. Fallback LIKE (если fulltext пустой)
        // ============================

        if ($posts->isEmpty()) {
            $posts = Post::query();

            foreach (explode(' ', $clean) as $word) {
                $posts->orWhere('title', 'LIKE', "%{$word}%")
                      ->orWhere('content', 'LIKE', "%{$word}%");
            }

            $posts = $posts
                ->with(['author', 'hashtags'])
                ->orderByDesc('created_at')
                ->get();
        }


        // ============================
        // 5. Поиск по комментариям
        // ============================

        $commentPostIds = Comment::where('content', 'LIKE', "%{$clean}%")
            ->pluck('post_id');

        $commentPosts = Post::whereIn('id', $commentPostIds)
            ->with(['author', 'hashtags'])
            ->orderByDesc('created_at')
            ->get();


        // ============================
        // 6. Убираем дубли
        // ============================

        $usedIds = collect()
            ->merge($tagPosts->pluck('id'))
            ->merge($posts->pluck('id'))
            ->unique();

        $commentPosts = $commentPosts->whereNotIn('id', $usedIds);

        // Убираем дубли между tagPosts и posts
        $posts = $posts->whereNotIn('id', $tagPosts->pluck('id'));


        // ============================
        // 7. Возвращаем всё
        // ============================

        return [
            'users'        => $users,
            'tagPosts'     => $tagPosts,
            'posts'        => $posts,
            'commentPosts' => $commentPosts,
        ];
    }
}
