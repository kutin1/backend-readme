<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewFollowerMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public User $recipient,
        public User $follower
    ) {
    }

    public function build()
    {
        return $this->subject('У вас новый подписчик')
            ->view('email.new_follower');
    }
}
