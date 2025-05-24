<?php

namespace Database\Factories;

use App\Models\SmsMessage;
use App\Models\User;
use App\Models\SmsTemplate;
use App\Models\SmsGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmsMessageFactory extends Factory
{
    protected $model = SmsMessage::class;

    public function definition(): array
    {
        $status = $this->faker->randomElement(['draft', 'scheduled', 'sending', 'sent', 'failed']);
        $scheduled = $this->faker->boolean(30);
        
        return [
            'sender_id' => User::factory(),
            'message' => $this->faker->paragraph(),
            'status' => $status,
            'template_id' => $this->faker->boolean(30) ? SmsTemplate::factory() : null,
            'group_id' => $this->faker->boolean(50) ? SmsGroup::factory() : null,
            'scheduled_at' => $scheduled ? $this->faker->dateTimeBetween('now', '+1 month') : null,
            'sent_at' => $status === 'sent' ? $this->faker->dateTimeBetween('-1 month') : null,
            'created_at' => $this->faker->dateTimeBetween('-1 month'),
            'updated_at' => $this->faker->dateTimeBetween('-1 month'),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'scheduled_at' => null,
            'sent_at' => null,
        ]);
    }

    public function sent(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'sent',
            'sent_at' => $this->faker->dateTimeBetween('-1 month'),
        ]);
    }

    public function scheduled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'scheduled',
            'scheduled_at' => $this->faker->dateTimeBetween('now', '+1 month'),
            'sent_at' => null,
        ]);
    }
}
