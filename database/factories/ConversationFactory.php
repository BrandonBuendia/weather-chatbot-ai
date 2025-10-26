<?php

namespace Database\Factories;

use App\Models\Conversation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Conversation>
 */
class ConversationFactory extends Factory
{
    protected $model = Conversation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
        ];
    }

    /**
     * Indicate that the conversation should have no title.
     */
    public function withoutTitle(): static
    {
        return $this->state(fn (array $attributes) => [
            'title' => null,
        ]);
    }

    /**
     * Indicate that the conversation should have a specific title.
     */
    public function withTitle(string $title): static
    {
        return $this->state(fn (array $attributes) => [
            'title' => $title,
        ]);
    }
}

# cGFuZ29saW4=
