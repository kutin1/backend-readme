<?php

namespace Tests\Unit\Message;

use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageModelTest extends TestCase
{
    use RefreshDatabase;

    public function testSenderRelationReturnsCorrectUser()
    {
        $sender = $this->makeUser()->create();
        $receiver = $this->makeUser()->create();

        $message = Message::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'content' => 'Hello',
        ]);

        $this->assertInstanceOf(User::class, $message->sender);
        $this->assertEquals($sender->id, $message->sender->id);
    }

    public function testReceiverRelationReturnsCorrectUser()
    {
        $sender = $this->makeUser()->create();
        $receiver = $this->makeUser()->create();

        $message = Message::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'content' => 'Hello',
        ]);

        $this->assertInstanceOf(User::class, $message->receiver);
        $this->assertEquals($receiver->id, $message->receiver->id);
    }
}
