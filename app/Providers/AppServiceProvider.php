<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use finfo;
use App\Services\Thumbnail\ThumbnailServiceInterface;
use App\Services\Thumbnail\ThumbnailService;
use Illuminate\Support\Facades\Auth;
use App\Services\MessageService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ThumbnailServiceInterface::class, ThumbnailService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Validator::extend('image_url_valid', function ($attribute, $value) {

            // Пытаемся скачать файл
            $contents = @file_get_contents($value);

            if (!$contents) {
                return false;
            }

            // Определяем MIME
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->buffer($contents);

            // MIME должен быть image/*
            return str_starts_with($mime, 'image/');
        });

        Validator::extend('youtube_url', function ($attribute, $value) {
            return preg_match('/(youtu\.be\/|youtube\.com\/)/i', $value);
        });


        view()->composer('layouts.app', function ($view) {
            $auth = Auth::user();

            $unread = 0;

            if ($auth) {
                $service = app(MessageService::class);
                $unread = $service->unreadCountTotal($auth->id);
            }

            $view->with('unreadMessages', $unread);
        });
    }
}
