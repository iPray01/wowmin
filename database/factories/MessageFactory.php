<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\MessageThread;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        return [
            'thread_id' => MessageThread::factory(),
            'sender_id' => User::factory(),
            'content' => $this->faker->paragraph(),
            'is_system_message' => $this->faker->boolean(10),
            'edited_at' => $this->faker->boolean(20) ? $this->faker->dateTimeBetween('-1 month') : null,
            'created_at' => $this->faker->dateTimeBetween('-1 month'),
            'updated_at' => $this->faker->dateTimeBetween('-1 month'),
        ];
    }

    public function system(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_system_message' => true,
            'content' => $this->faker->randomElement([
                'Thread created',
                'User added to thread',
                'User left thread',
                'Thread archived',
                'Thread restored'
            ]),
        ]);
    }

    public function edited(): static
    {
        return $this->state(fn (array $attributes) => [
            'edited_at' => $this->faker->dateTimeBetween('-1 month'),
        ]);
    }
}
