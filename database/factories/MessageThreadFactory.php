<?php

namespace Database\Factories;

use App\Models\MessageThread;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageThreadFactory extends Factory
{
    protected $model = MessageThread::class;

    public function definition(): array
    {
        return [
            'subject' => $this->faker->sentence(),
            'created_by' => User::factory(),
            'is_archived' => $this->faker->boolean(20),
            'last_message_at' => $this->faker->dateTimeBetween('-1 month'),
            'created_at' => $this->faker->dateTimeBetween('-1 month'),
            'updated_at' => $this->faker->dateTimeBetween('-1 month'),
        ];
    }

    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_archived' => true,
        ]);
    }

    public function withParticipants(int $count = 2): static
    {
        return $this->afterCreating(function (MessageThread $thread) use ($count) {
            $users = User::factory()->count($count)->create();
            foreach ($users as $user) {
                $thread->participants()->create([
                    'user_id' => $user->id,
                    'last_read_at' => $this->faker->dateTimeBetween('-1 month'),
                    'is_muted' => $this->faker->boolean(10),
                ]);
            }
        });
    }
}
