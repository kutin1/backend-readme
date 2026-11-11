<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;

use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\FeedController;

use App\Http\Controllers\MessageController;
use App\Http\Controllers\SearchController;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->get('/profile', function () {
    $user = auth()->user();

    return response()->json([
        'id' => $user->id,
        'email' => $user->email,
        'login' => $user->login,
    ]);
});

Route::middleware('auth:sanctum')->post('/logout', function () {
    auth()->user()->currentAccessToken()->delete();

    return response()->json(['message' => 'Logged out']);
});

Route::middleware('auth:sanctum')->post('/posts', [PostController::class, 'store']);

Route::middleware('auth:sanctum')->post('/posts/{post}/like', [LikeController::class, 'store']);

Route::middleware('auth:sanctum')->post('/posts/{post}/repost', [PostController::class, 'repost']);

Route::middleware('auth:sanctum')->post('/comments', [CommentController::class, 'store']);

Route::middleware('auth:sanctum')->post('/users/{user}/subscribe', [SubscriptionController::class, 'subscribe']);
Route::middleware('auth:sanctum')->delete('/users/{user}/unsubscribe', [SubscriptionController::class, 'unsubscribe']);

Route::middleware('auth:sanctum')->get('/feed', [FeedController::class, 'index']);

Route::middleware('auth:sanctum')->post('/messages', [MessageController::class, 'store']);

Route::get('/search', SearchController::class);

