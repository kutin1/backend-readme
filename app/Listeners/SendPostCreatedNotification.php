<?php

namespace App\Listeners;

use App\Events\PostCreated;
use App\Jobs\SendEmailJob;
use App\Mail\PostCreatedMail;

class SendPostCreatedNotification
{
    public function handle(PostCreated $event): void
    {
        $post = $event->post;
        $author = $post->author;

        // Берём всех подписчиков автора
        foreach ($author->subscribers as $subscriber) {
            if (!$subscriber->email) {
                continue; // пропускаем юзеров без email
            }

            SendEmailJob::dispatch(
                $subscriber->email,
                new PostCreatedMail($post)
            );
        }
    }
}
