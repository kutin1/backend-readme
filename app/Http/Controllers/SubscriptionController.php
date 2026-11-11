<?php

namespace App\Http\Controllers;

use App\Services\SubscriptionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class SubscriptionController extends Controller
{
    public function __construct(
        private readonly SubscriptionService $subscriptionService
    ) {
    }

    public function subscribe(int $userId): JsonResponse
    {
        $this->subscriptionService->subscribe(Auth::user(), $userId);

        return response()->json([
            'status' => 'success',
            'message' => 'Subscribed',
        ]);
    }

    public function unsubscribe(int $userId): JsonResponse
    {
        $this->subscriptionService->unsubscribe(Auth::user(), $userId);

        return response()->json([
            'status' => 'success',
            'message' => 'Unsubscribed',
        ]);
    }
}
