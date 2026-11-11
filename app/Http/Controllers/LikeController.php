<?php

namespace App\Http\Controllers;

use App\Services\LikeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function __construct(
        private readonly LikeService $likeService
    ) {
    }

    public function store(int $postId): JsonResponse
    {
        $this->likeService->like(Auth::user(), $postId);

        return response()->json([
            'status' => 'success',
            'message' => 'Post liked',
        ]);
    }
}
