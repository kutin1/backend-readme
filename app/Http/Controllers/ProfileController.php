<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use App\Jobs\SendEmailJob;
use App\Mail\NewFollowerMail;

class ProfileController extends Controller
{
    public function subscribe(User $user)
    {

        $follower = Auth::user();

        if ($user->id === $follower->id) {
            abort(403);
        }

        if ($user->id !== $follower->id) {
            $subscription = Subscription::firstOrCreate([
                'subscriber_id' => $follower->id,
                'target_id' => $user->id,
            ]);

            if ($subscription->wasRecentlyCreated) {
                SendEmailJob::dispatch(
                    $user->email,
                    new NewFollowerMail($user, $follower)
                )->delay(now()->addSeconds(5));
            }
        }

        return back();
    }

    public function unsubscribe(User $user)
    {
        Subscription::where('subscriber_id', Auth::id())
            ->where('target_id', $user->id)
            ->delete();

        return back();
    }

    public function posts(User $user)
    {
        $posts = $user->posts()
            ->with([
                'author',
                'hashtags',
                'originalPost',
                'originalPost.author',
                'originalPost.hashtags'
            ])
            ->latest()
            ->get();

        $isSubscribed = Subscription::where('subscriber_id', Auth::id())
            ->where('target_id', $user->id)
            ->exists();

        return view('profile.index', [
            'user' => $user,
            'activeTab' => 'posts',
            'posts' => $posts,
            'likedPosts' => collect(),
            'subscriptions' => collect(),
            'isSubscribed' => $isSubscribed,
        ]);
    }

    public function likes(User $user)
    {

        $likedPosts = $user->likedPosts()
            ->latest()
            ->get();

        $isSubscribed = Subscription::where('subscriber_id', Auth::id())
            ->where('target_id', $user->id)
            ->exists();

        return view('profile.index', [
            'user' => $user,
            'activeTab' => 'likes',
            'posts' => collect(),
            'likedPosts' => $likedPosts,
            'subscriptions' => collect(),
            'isSubscribed' => $isSubscribed,
        ]);
    }


    public function subscriptions(User $user)
    {
        $subscriptions = $user->subscriptions()
            ->with('target')
            ->get()
            ->pluck('target');

        $isSubscribed = Subscription::where('subscriber_id', Auth::id())
            ->where('target_id', $user->id)
            ->exists();

        return view('profile.index', [
            'user' => $user,
            'activeTab' => 'subscriptions',
            'posts' => collect(),
            'likedPosts' => collect(),
            'subscriptions' => $subscriptions,
            'isSubscribed' => $isSubscribed,
        ]);
    }
}
