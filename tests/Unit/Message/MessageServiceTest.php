<?php

namespace Tests\Unit\Message;

use App\Models\Message;
use App\Models\User;
use App\Services\MessageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageServiceTest extends TestCase
{
    use RefreshDatabase;

    private MessageService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new MessageService();
    }

    public function testSendCreatesMessage()
    {
        $sender = $this->makeUser()->create();
        $receiver = $this->makeUser()->create();

        $msg = $this->service->send($sender, $receiver->id, ' Hello ');

        $this->assertInstanceOf(Message::class, $msg);
        $this->assertEquals($sender->id, $msg->sender_id);
        $this->assertEquals($receiver->id, $msg->receiver_id);
        $this->assertEquals('Hello', $msg->content); // trim()
    }

    public function testContactsReturnsAllUniqueDialogUsers()
    {
        $userA = $this->makeUser()->create();
        $userB = $this->makeUser()->create();
        $userC = $this->makeUser()->create();

        // A ↔ B
        Message::create([
            'sender_id' => $userA->id,
            'receiver_id' => $userB->id,
            'content' => 'Hi',
        ]);

        // C → A
        Message::create([
            'sender_id' => $userC->id,
            'receiver_id' => $userA->id,
            'content' => 'Hello',
        ]);

        $contacts = $this->service->contacts($userA->id);

        $this->assertCount(2, $contacts);
        $this->assertTrue($contacts->contains('id', $userB->id));
        $this->assertTrue($contacts->contains('id', $userC->id));
    }

    public function testDialogReturnsMessagesBetweenTwoUsers()
    {
        $userA = $this->makeUser()->create();
        $userB = $this->makeUser()->create();

        $m1 = Message::create([
            'sender_id' => $userA->id,
            'receiver_id' => $userB->id,
            'content' => 'A→B',
        ]);

        $m2 = Message::create([
            'sender_id' => $userB->id,
            'receiver_id' => $userA->id,
            'content' => 'B→A',
        ]);

        $dialog = $this->service->dialog($userA->id, $userB->id);

        $this->assertCount(2, $dialog);
        $this->assertEquals('A→B', $dialog[0]->content);
        $this->assertEquals('B→A', $dialog[1]->content);
    }

    public function testLastMessageBetweenReturnsLatestMessage()
    {
        $userA = $this->makeUser()->create();
        $userB = $this->makeUser()->create();

        $this->makeMessage()->create([
            'sender_id' => $userA->id,
            'receiver_id' => $userB->id,
            'content' => 'Old',
            'created_at' => now()->subMinute(),
        ]);

        $this->makeMessage()->create([
            'sender_id' => $userB->id,
            'receiver_id' => $userA->id,
            'content' => 'Latest',
            'created_at' => now(),
        ]);

        $result = $this->service->lastMessageBetween($userA->id, $userB->id);

        $this->assertEquals('Latest', $result->content);
    }


    public function testMarkAsReadUpdatesOnlyUnreadMessages()
    {
        $userA = $this->makeUser()->create();
        $userB = $this->makeUser()->create();

        $msg = Message::create([
            'sender_id' => $userB->id,
            'receiver_id' => $userA->id,
            'content' => 'Unread',
            'read_at' => null,
        ]);

        $this->service->markAsRead($userA->id, $userB->id);

        $this->assertNotNull($msg->fresh()->read_at);
    }

    public function testUnreadCountReturnsCorrectNumber()
    {
        $userA = $this->makeUser()->create();
        $userB = $this->makeUser()->create();

        $this->makeMessage()->count(3)->create([
            'sender_id' => $userB->id,
            'receiver_id' => $userA->id,
            'read_at' => null,
        ]);

        $this->makeMessage()->create([
            'sender_id' => $userB->id,
            'receiver_id' => $userA->id,
            'read_at' => now(),
        ]);

        $count = $this->service->unreadCount($userA->id, $userB->id);

        $this->assertEquals(3, $count);
    }

    public function testUnreadCountTotalReturnsAllUnreadMessages()
    {
        $userA = $this->makeUser()->create();
        $userB = $this->makeUser()->create();
        $userC = $this->makeUser()->create();

        $this->makeMessage()->create([
            'sender_id' => $userB->id,
            'receiver_id' => $userA->id,
            'read_at' => null,
        ]);

        $this->makeMessage()->create([
            'sender_id' => $userC->id,
            'receiver_id' => $userA->id,
            'read_at' => null,
        ]);

        $count = $this->service->unreadCountTotal($userA->id);

        $this->assertEquals(2, $count);
    }
}
