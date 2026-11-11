<?php

namespace Tests\Feature\Message;

use App\Models\User;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanSendMessage()
    {
        $sender = $this->makeUser()->create();
        $receiver = $this->makeUser()->create();

        $this->actingAs($sender);

        $response = $this->post(route('messages.store', $receiver), [
            'receiver_id' => $receiver->id,
            'content' => 'Hello!',
        ]);

        $response->assertRedirect(route('messages.show', $receiver));

        $this->assertDatabaseHas('messages', [
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'content' => 'Hello!',
        ]);
    }

    public function testUserCannotSendMessageToSelf()
    {
        $user = $this->makeUser()->create();

        $this->actingAs($user);

        $response = $this->post(route('messages.store', $user), [
            'receiver_id' => $user->id,
            'content' => 'Self message',
        ]);

        $response->assertSessionHasErrors('receiver_id');
    }

    public function testContentIsRequired()
    {
        $sender = $this->makeUser()->create();
        $receiver = $this->makeUser()->create();

        $this->actingAs($sender);

        $response = $this->post(route('messages.store', $receiver), [
            'receiver_id' => $receiver->id,
            'content' => '',
        ]);

        $response->assertSessionHasErrors('content');
    }

    public function testIndexReturnsContactsAndFirstDialog()
    {
        $userA = $this->makeUser()->create();
        $userB = $this->makeUser()->create();

        Message::create([
            'sender_id' => $userA->id,
            'receiver_id' => $userB->id,
            'content' => 'Hi',
        ]);

        $this->actingAs($userA);

        $response = $this->get(route('messages.index'));

        $response->assertStatus(200);
        $response->assertViewHas('contacts');
        $response->assertViewHas('activeUser');
        $response->assertViewHas('messages');
    }

    public function testShowOpensDialogAndMarksMessagesAsRead()
    {
        $userA = $this->makeUser()->create();
        $userB = $this->makeUser()->create();

        $msg = Message::create([
            'sender_id' => $userB->id,
            'receiver_id' => $userA->id,
            'content' => 'Unread',
            'read_at' => null,
        ]);

        $this->actingAs($userA);

        $response = $this->get(route('messages.show', $userB));

        $response->assertStatus(200);

        $this->assertNotNull($msg->fresh()->read_at);
    }

    public function testGuestCannotSendMessage()
    {
        $sender = $this->makeUser()->create();

        $response = $this->post(route('messages.store', $sender), [
            'receiver_id' => $sender->id,
            'content' => 'Hello',
        ]);

        $response->assertRedirect(route('login'));
    }
}
