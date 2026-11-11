<?php

namespace App\Mail;

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PostCreatedMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public User $recipient,
        public Post $post
    ) {
    }

    public function build()
    {
        return $this->subject('Новая публикация от пользователя ' . $this->post->author->login)
            ->view('email.post_created');
    }
}
