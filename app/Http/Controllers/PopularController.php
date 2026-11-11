<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PopularController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'popular');
        $type = $request->get('type', 'all');
        $direction = $request->get('direction', 'desc');

        $query = Post::with([
            'author',
            'hashtags',
            'contentType',
            'originalPost',
            'originalPost.author',
            'originalPost.hashtags'
        ])
        ->withCount(['likes', 'comments']);

        // Фильтр по типу
        if ($type !== 'all') {
            $query->whereHas('contentType', function ($q) use ($type) {
                $q->where('icon', $type);
            });
        }

        // Сортировка
        switch ($sort) {
            case 'likes':
                $query->orderBy('likes_count', $direction);

                break;

            case 'date':
                $query->orderBy('created_at', $direction);

                break;

            default: // popular
                $query->orderBy('likes_count', $direction)
                    ->orderBy('comments_count', $direction);

                break;
        }

        $posts = $query->paginate(6)
               ->appends([
                   'sort' => $sort,
                   'type' => $type,
                   'direction' => $direction,
               ]);

        return view('popular.index', compact('posts', 'sort', 'type', 'direction'));
    }
}
