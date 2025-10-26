<?php

namespace Database\Factories;

use App\Enums\MessageRole;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    protected $model = Message::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'conversation_id' => Conversation::factory(),
            'role' => fake()->randomElement([MessageRole::USER, MessageRole::ASSISTANT]),
            'content' => fake()->paragraph(),
            'metadata' => null,
        ];
    }

    /**
     * Indicate that the message is from a user.
     */
    public function user(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => MessageRole::USER,
        ]);
    }

    /**
     * Indicate that the message is from an assistant.
     */
    public function assistant(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => MessageRole::ASSISTANT,
        ]);
    }

    /**
     * Indicate that the message belongs to a specific conversation.
     */
    public function forConversation(Conversation $conversation): static
    {
        return $this->state(fn (array $attributes) => [
            'conversation_id' => $conversation->id,
        ]);
    }

    /**
     * Indicate that the message has metadata.
     */
    public function withMetadata(array $metadata): static
    {
        return $this->state(fn (array $attributes) => [
            'metadata' => $metadata,
        ]);
    }
}

# cGFuZ29saW4=
