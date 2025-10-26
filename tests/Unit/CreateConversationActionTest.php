<?php

namespace Tests\Unit;

use App\Actions\CreateConversationAction;
use App\Models\Conversation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateConversationActionTest extends TestCase
{
    use RefreshDatabase;

    protected CreateConversationAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new CreateConversationAction();
    }

    public function test_creates_conversation_with_default_title(): void
    {
        $conversation = $this->action->execute();

        $this->assertInstanceOf(Conversation::class, $conversation);
        $this->assertEquals('Nueva conversación', $conversation->title);
        $this->assertDatabaseHas('conversations', [
            'id' => $conversation->id,
            'title' => 'Nueva conversación',
        ]);
    }

    public function test_creates_conversation_with_custom_title(): void
    {
        $customTitle = 'Conversación sobre el clima';

        $conversation = $this->action->execute($customTitle);

        $this->assertInstanceOf(Conversation::class, $conversation);
        $this->assertEquals($customTitle, $conversation->title);
        $this->assertDatabaseHas('conversations', [
            'id' => $conversation->id,
            'title' => $customTitle,
        ]);
    }

    public function test_creates_conversation_with_null_title(): void
    {
        $conversation = $this->action->execute(null);

        $this->assertInstanceOf(Conversation::class, $conversation);
        $this->assertEquals('Nueva conversación', $conversation->title);
    }

    public function test_creates_conversation_with_empty_string_uses_empty_string(): void
    {
        $conversation = $this->action->execute('');

        $this->assertInstanceOf(Conversation::class, $conversation);
        $this->assertEquals('', $conversation->title);
    }

    public function test_conversation_has_timestamps(): void
    {
        $conversation = $this->action->execute();

        $this->assertNotNull($conversation->created_at);
        $this->assertNotNull($conversation->updated_at);
    }

    public function test_can_create_multiple_conversations(): void
    {
        $conversation1 = $this->action->execute('Primera conversación');
        $conversation2 = $this->action->execute('Segunda conversación');
        $conversation3 = $this->action->execute('Tercera conversación');

        $this->assertNotEquals($conversation1->id, $conversation2->id);
        $this->assertNotEquals($conversation2->id, $conversation3->id);
        $this->assertDatabaseCount('conversations', 3);
    }

    public function test_conversation_starts_with_no_messages(): void
    {
        $conversation = $this->action->execute();

        $this->assertCount(0, $conversation->messages);
    }

    public function test_created_conversation_is_persisted(): void
    {
        $conversation = $this->action->execute('Test Title');

        $this->assertTrue($conversation->exists);
        $this->assertNotNull($conversation->id);
    }
}

# cGFuZ29saW4=
