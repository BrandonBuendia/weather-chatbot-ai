<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\Message;
use App\Services\OpenAIService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class ChatControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_index_displays_all_conversations_with_latest_message(): void
    {
        $conversation1 = Conversation::factory()->create([
            'title' => 'Conversación 1',
            'created_at' => now()->subHours(2),
        ]);
        $conversation2 = Conversation::factory()->create([
            'title' => 'Conversación 2',
            'created_at' => now()->subHours(1),
        ]);

        Message::factory()->user()->forConversation($conversation1)->create([
            'content' => 'Primer mensaje',
        ]);
        Message::factory()->assistant()->forConversation($conversation1)->create([
            'content' => 'Respuesta del asistente',
        ]);

        Message::factory()->user()->forConversation($conversation2)->create([
            'content' => 'Otro mensaje',
        ]);

        $response = $this->get(route('chat.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Chat/Index')
            ->has('conversations', 2)
        );
    }

    public function test_show_displays_conversation_with_all_messages(): void
    {
        $conversation = Conversation::factory()->create(['title' => 'Test Conversation']);

        Message::factory()->user()->forConversation($conversation)->create([
            'content' => 'Hola, ¿qué tiempo hace en Madrid?',
        ]);
        Message::factory()->assistant()->forConversation($conversation)->create([
            'content' => 'El clima en Madrid es soleado con 25°C',
        ]);

        $response = $this->get(route('chat.show', $conversation));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Chat/Show')
            ->has('conversation.messages', 2)
            ->where('conversation.title', 'Test Conversation')
        );
    }

    public function test_store_creates_new_conversation_and_redirects_to_chat(): void
    {
        $response = $this->post(route('chat.store'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Chat/Show')
            ->has('conversation')
        );

        $this->assertDatabaseCount('conversations', 1);
        $this->assertDatabaseHas('conversations', [
            'title' => 'Nueva conversación',
        ]);
    }

    public function test_send_message_creates_user_message_and_assistant_response(): void
    {
        $conversation = Conversation::factory()->create();

        $mockOpenAIService = Mockery::mock(OpenAIService::class);
        $mockOpenAIService->shouldReceive('chat')
            ->once()
            ->andReturn('El clima en Barcelona es soleado con 22°C');

        $this->app->instance(OpenAIService::class, $mockOpenAIService);

        $response = $this->postJson(
            route('chat.messages.store', $conversation),
            ['content' => '¿Qué tiempo hace en Barcelona?']
        );

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseCount('messages', 2);

        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'role' => 'user',
            'content' => '¿Qué tiempo hace en Barcelona?',
        ]);

        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'role' => 'assistant',
            'content' => 'El clima en Barcelona es soleado con 22°C',
        ]);
    }

    public function test_send_message_validates_content_is_required(): void
    {
        $conversation = Conversation::factory()->create();

        $response = $this->postJson(
            route('chat.messages.store', $conversation),
            ['content' => '']
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['content']);
    }

    public function test_send_message_validates_content_max_length(): void
    {
        $conversation = Conversation::factory()->create();

        $response = $this->postJson(
            route('chat.messages.store', $conversation),
            ['content' => str_repeat('a', 1001)]
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['content']);
    }

    public function test_send_message_returns_error_on_exception(): void
    {
        $conversation = Conversation::factory()->create();

        $mockOpenAIService = Mockery::mock(OpenAIService::class);
        $mockOpenAIService->shouldReceive('chat')
            ->once()
            ->andThrow(new \Exception('API Error'));

        $this->app->instance(OpenAIService::class, $mockOpenAIService);

        $response = $this->postJson(
            route('chat.messages.store', $conversation),
            ['content' => 'Test message']
        );

        $response->assertStatus(500);
        $response->assertJson([
            'success' => false,
            'message' => 'Error al enviar el mensaje. Por favor, intenta nuevamente.',
        ]);
    }

    public function test_conversation_messages_are_ordered_chronologically(): void
    {
        $conversation = Conversation::factory()->create();

        $message1 = Message::factory()->user()->forConversation($conversation)->create([
            'content' => 'Primer mensaje',
            'created_at' => now()->subMinutes(5),
        ]);

        $message2 = Message::factory()->assistant()->forConversation($conversation)->create([
            'content' => 'Segundo mensaje',
            'created_at' => now()->subMinutes(3),
        ]);

        $message3 = Message::factory()->user()->forConversation($conversation)->create([
            'content' => 'Tercer mensaje',
            'created_at' => now()->subMinutes(1),
        ]);

        $response = $this->get(route('chat.show', $conversation));

        $response->assertInertia(fn ($page) => $page
            ->has('conversation.messages', 3)
            ->where('conversation.messages.0.content', 'Primer mensaje')
            ->where('conversation.messages.1.content', 'Segundo mensaje')
            ->where('conversation.messages.2.content', 'Tercer mensaje')
        );
    }
}

# cGFuZ29saW4=
