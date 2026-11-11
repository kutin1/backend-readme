<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PopularController;

// =========================
// Публичные страницы (гости и авторизованные)
// =========================

// Все страницы для разработки
Route::get('/', fn() => view('dev.index'))->name('index');

// =========================
// Страницы только для гостей (guest)
// =========================

Route::middleware('guest')->group(function () {

    // Главная
    Route::get('/main', fn() => view('main.index'))->name('main');

    // Логин
    Route::get('/login', [LoginController::class, 'show'])
        ->name('login');

    // Логин
    Route::post('/login', [LoginController::class, 'login'])
        ->name('login.perform');

    // Регистрация
    Route::get('/register', [RegisterController::class, 'show'])
        ->name('register.show');

    // Регистрация
    Route::post('/register', [RegisterController::class, 'register'])
        ->name('register.perform');

});

// =========================
// Страницы только для авторизованных (auth)
// =========================

    Route::middleware('auth')->group(function () {

    // Выход
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    })->name('logout');

    // Лента
    Route::get('/feed', [FeedController::class, 'page'])
        ->name('feed');

    // Сообщения
    Route::get('/messages', [MessageController::class, 'index'])
        ->name('messages.index');

    Route::get('/messages/{user}', [MessageController::class, 'show'])
        ->name('messages.show');

    Route::post('/messages/{user}', [MessageController::class, 'store'])
        ->name('messages.store');


    // Популярное
    Route::get('/popular', [PopularController::class, 'index'])
        ->name('popular.index');

    // Создание поста
    Route::get('/post/create', function (Illuminate\Http\Request $request) {
        $type = $request->get('type', 'photo'); // по умолчанию фото
        return view('post.create', compact('type'));
    })->name('post.create');

    Route::post('/posts', [PostController::class, 'store'])
    ->name('posts.store');

    // Пост
    Route::get('/post/{post}', [PostController::class, 'show'])->name('post.show');

    // Профиль
    Route::get('/profile/{user}', [ProfileController::class, 'posts'])
        ->name('profile.posts');

    Route::post('/post/{post}/comments', [CommentController::class, 'store'])
    ->name('comments.store');


    Route::get('/profile/{user}/likes', [ProfileController::class, 'likes'])
        ->name('profile.likes');

    Route::post('/post/{post}/like', [PostController::class, 'like'])
        ->name('post.like');

    Route::post('/post/{post}/repost', [PostController::class, 'repost'])
        ->name('post.repost');

    Route::get('/profile/{user}/subscriptions', [ProfileController::class, 'subscriptions'])
        ->name('profile.subscriptions');

    Route::post('/profile/{user}/subscribe', [ProfileController::class, 'subscribe'])
        ->name('profile.subscribe');

    Route::post('/profile/{user}/unsubscribe', [ProfileController::class, 'unsubscribe'])
        ->name('profile.unsubscribe');

    // Результаты поиска
    Route::get('/search', SearchController::class)->name('search');


});


