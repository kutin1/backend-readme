<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Post;
use App\Services\CommentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct(
        private readonly CommentService $commentService
    ) {
    }

    /**
     * Универсальный метод:
     * - если AJAX → вернуть JSON
     * - если обычная форма → редирект назад
     */
    public function store(CommentRequest $request, Post $post)
    {
        // Создаём комментарий через сервис
        $comment = $this->commentService->create(
            Auth::user(),
            $post->id,
            $request->content
        );

        // Если запрос AJAX — вернуть JSON
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'data' => $comment->load('author'),
            ], 201);
        }

        // Если обычная форма — редирект назад
        return back();
    }
}
