<?php

namespace App\Http\Controllers;

use App\Services\FeedService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class FeedController extends Controller
{
    public function __construct(
        private readonly FeedService $feedService
    ) {
    }

    public function page(Request $request)
    {
        $type = $request->get('type', 'all');

        $posts = $this->feedService->getFeed(Auth::user(), $type);

        return view('feed.index', compact('posts', 'type'));
    }
}
