<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\PostRequest;
use App\Services\PostService;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Jobs\SendEmailJob;
use App\Mail\PostCreatedMail;
use App\Models\Subscription;

class PostController extends Controller
{
    public function __construct(
        private readonly PostService $postService
    ) {
    }

    public function store(PostRequest $request)
    {

        $author = Auth::user();

        $post = $this->postService->create(
            $request->validated(),
            $author
        );

        // Уведомляем подписчиков автора
        $subscribers = Subscription::where('target_id', $author->id)
            ->with('subscriber')
            ->get()
            ->pluck('subscriber');

        foreach ($subscribers as $subscriber) {
            SendEmailJob::dispatch(
                $subscriber->email,
                new PostCreatedMail($subscriber, $post)
            )->delay(now()->addSeconds(5));
        }


        // Если запрос AJAX — вернуть JSON
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'data' => $post->load('hashtags', 'author'),
            ], 201);
        }

        return redirect()
            ->route('feed')
            ->with('post_added', true);
    }

    public function show(Post $post)
    {
        $post->increment('views');

        return view('post.details', compact('post'));
    }

    public function like(Post $post)
    {
        $user = auth()->user();

        // Если уже лайкал — убираем лайк
        if ($post->likes()->where('user_id', $user->id)->exists()) {
            $post->likes()->where('user_id', $user->id)->delete();
        } else {
            $post->likes()->create(['user_id' => $user->id]);
        }

        return back();
    }


    public function repost(Post $post)
    {
        $new = $this->postService->repost($post, Auth::user());
        return redirect()->route('profile.posts', Auth::id());
    }
}
