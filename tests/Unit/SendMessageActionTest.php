<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Actions\SendMessageAction;
use App\Enums\MessageRole;
use App\Models\Conversation;
use App\Models\Message;
use App\Services\OpenAIService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class SendMessageActionTest extends TestCase
{
    use RefreshDatabase;

    protected SendMessageAction $action;
    protected $mockOpenAIService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockOpenAIService = Mockery::mock(OpenAIService::class);
        $this->action = new SendMessageAction($this->mockOpenAIService);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_creates_user_message(): void
    {
        $conversation = Conversation::factory()->create();

        $this->mockOpenAIService
            ->shouldReceive('chat')
            ->once()
            ->andReturn('Respuesta del asistente');

        $this->action->execute($conversation, '¿Qué tiempo hace en Madrid?');

        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'role' => 'user',
            'content' => '¿Qué tiempo hace en Madrid?',
        ]);
    }

    public function test_creates_assistant_message(): void
    {
        $conversation = Conversation::factory()->create();
        $assistantResponse = 'El clima en Madrid es soleado con 25°C';

        $this->mockOpenAIService
            ->shouldReceive('chat')
            ->once()
            ->andReturn($assistantResponse);

        $message = $this->action->execute($conversation, 'Test content');

        $this->assertInstanceOf(Message::class, $message);
        $this->assertEquals(MessageRole::ASSISTANT, $message->role);
        $this->assertEquals($assistantResponse, $message->content);
        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'role' => 'assistant',
            'content' => $assistantResponse,
        ]);
    }

    public function test_passes_conversation_history_to_openai_service(): void
    {
        $conversation = Conversation::factory()->create();

        Message::factory()->user()->forConversation($conversation)->create([
            'content' => 'Mensaje 1',
        ]);
        Message::factory()->assistant()->forConversation($conversation)->create([
            'content' => 'Respuesta 1',
        ]);

        $this->mockOpenAIService
            ->shouldReceive('chat')
            ->once()
            ->withArgs(function ($messages, $conversationId) use ($conversation) {
                return count($messages) === 3 &&
                       $messages[0]['role'] === 'user' &&
                       $messages[0]['content'] === 'Mensaje 1' &&
                       $messages[1]['role'] === 'assistant' &&
                       $messages[1]['content'] === 'Respuesta 1' &&
                       $messages[2]['role'] === 'user' &&
                       $conversationId === $conversation->id;
            })
            ->andReturn('Nueva respuesta');

        $this->action->execute($conversation, 'Nuevo mensaje');
    }

    public function test_returns_assistant_message(): void
    {
        $conversation = Conversation::factory()->create();

        $this->mockOpenAIService
            ->shouldReceive('chat')
            ->once()
            ->andReturn('Respuesta del asistente');

        $result = $this->action->execute($conversation, 'Test message');

        $this->assertInstanceOf(Message::class, $result);
        $this->assertEquals(MessageRole::ASSISTANT, $result->role);
        $this->assertEquals('Respuesta del asistente', $result->content);
    }

    public function test_creates_two_messages_total(): void
    {
        $conversation = Conversation::factory()->create();

        $this->mockOpenAIService
            ->shouldReceive('chat')
            ->once()
            ->andReturn('Respuesta');

        $this->action->execute($conversation, 'Test content');

        $this->assertDatabaseCount('messages', 2);
    }

    public function test_messages_belong_to_correct_conversation(): void
    {
        $conversation1 = Conversation::factory()->create();
        $conversation2 = Conversation::factory()->create();

        $this->mockOpenAIService
            ->shouldReceive('chat')
            ->twice()
            ->andReturn('Respuesta');

        $this->action->execute($conversation1, 'Mensaje para conversación 1');
        $this->action->execute($conversation2, 'Mensaje para conversación 2');

        $this->assertEquals(2, $conversation1->messages()->count());
        $this->assertEquals(2, $conversation2->messages()->count());
    }

    public function test_preserves_message_order(): void
    {
        $conversation = Conversation::factory()->create();

        $this->mockOpenAIService
            ->shouldReceive('chat')
            ->times(3)
            ->andReturn('Respuesta 1', 'Respuesta 2', 'Respuesta 3');

        $this->action->execute($conversation, 'Mensaje 1');
        sleep(1);
        $this->action->execute($conversation, 'Mensaje 2');
        sleep(1);
        $this->action->execute($conversation, 'Mensaje 3');

        $messages = $conversation->messages()->orderBy('created_at')->get();

        $this->assertCount(6, $messages);
        $this->assertEquals('Mensaje 1', $messages[0]->content);
        $this->assertEquals('Respuesta 1', $messages[1]->content);
        $this->assertEquals('Mensaje 2', $messages[2]->content);
    }

    public function test_handles_long_content(): void
    {
        $conversation = Conversation::factory()->create();
        $longContent = str_repeat('Test message ', 100);

        $this->mockOpenAIService
            ->shouldReceive('chat')
            ->once()
            ->andReturn('Respuesta');

        $this->action->execute($conversation, $longContent);

        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'role' => 'user',
            'content' => $longContent,
        ]);
    }

    public function test_handles_special_characters(): void
    {
        $conversation = Conversation::factory()->create();
        $specialContent = '¿Qué tiempo hace en París? ñ á é í ó ú 🌤️';

        $this->mockOpenAIService
            ->shouldReceive('chat')
            ->once()
            ->andReturn('Respuesta');

        $this->action->execute($conversation, $specialContent);

        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'content' => $specialContent,
        ]);
    }

    public function test_messages_have_timestamps(): void
    {
        $conversation = Conversation::factory()->create();

        $this->mockOpenAIService
            ->shouldReceive('chat')
            ->once()
            ->andReturn('Respuesta');

        $message = $this->action->execute($conversation, 'Test');

        $this->assertNotNull($message->created_at);
        $this->assertNotNull($message->updated_at);
    }
}

# cGFuZ29saW4=
