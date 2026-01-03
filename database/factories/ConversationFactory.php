<?php

namespace Database\Factories;

use App\Models\Conversation;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConversationFactory extends Factory
{
    protected $model = Conversation::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'image' => null,
            'type' => 'global',
            'created_by' => null,
            'is_encrypted' => false,
        ];
    }

    public function private(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'private',
            'name' => null,
        ]);
    }

    public function global(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'global',
        ]);
    }
}
