<?php

namespace Tests\Feature\Message;

use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageListTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexReturnsEmptyDataIfNoContacts()
    {
        $user = $this->makeUser()->create();

        $this->actingAs($user);

        $response = $this->get(route('messages.index'));

        $response->assertStatus(200);

        $response->assertViewHas('contacts', []);
        $response->assertViewHas('activeUser', null);
        $response->assertViewHas('messages', []);
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

        $contacts = $response->viewData('contacts');
        $activeUser = $response->viewData('activeUser');
        $messages = $response->viewData('messages');

        $this->assertCount(1, $contacts);
        $this->assertEquals($userB->id, $activeUser->id);
        $this->assertCount(1, $messages);
    }

    public function testShowAddsUserToContactsIfNotExists()
    {
        $userA = $this->makeUser()->create();
        $userB = $this->makeUser()->create();

        $this->actingAs($userA);

        $response = $this->get(route('messages.show', $userB));

        $response->assertStatus(200);

        $contacts = $response->viewData('contacts');

        $this->assertTrue($contacts->contains('id', $userB->id));
    }

    public function testShowMarksMessagesAsRead()
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

    public function testShowReturnsCorrectDialog()
    {
        $userA = $this->makeUser()->create();
        $userB = $this->makeUser()->create();

        Message::create([
            'sender_id' => $userA->id,
            'receiver_id' => $userB->id,
            'content' => 'A→B',
        ]);

        Message::create([
            'sender_id' => $userB->id,
            'receiver_id' => $userA->id,
            'content' => 'B→A',
        ]);

        $this->actingAs($userA);

        $response = $this->get(route('messages.show', $userB));

        $response->assertStatus(200);

        $messages = $response->viewData('messages');

        $this->assertCount(2, $messages);
        $this->assertEquals('A→B', $messages[0]->content);
        $this->assertEquals('B→A', $messages[1]->content);
    }
}
