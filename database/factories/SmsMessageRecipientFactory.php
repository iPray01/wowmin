<?php

namespace Database\Factories;

use App\Models\SmsMessageRecipient;
use App\Models\SmsMessage;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmsMessageRecipientFactory extends Factory
{
    protected $model = SmsMessageRecipient::class;

    public function definition(): array
    {
        $status = $this->faker->randomElement(['pending', 'sent', 'delivered', 'failed']);
        
        return [
            'message_id' => SmsMessage::factory(),
            'member_id' => Member::factory(),
            'phone_number' => $this->faker->phoneNumber(),
            'status' => $status,
            'error_message' => $status === 'failed' ? $this->faker->sentence() : null,
            'sent_at' => in_array($status, ['sent', 'delivered']) ? $this->faker->dateTimeBetween('-1 month') : null,
            'delivered_at' => $status === 'delivered' ? $this->faker->dateTimeBetween('-1 month') : null,
            'created_at' => $this->faker->dateTimeBetween('-1 month'),
            'updated_at' => $this->faker->dateTimeBetween('-1 month'),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'sent_at' => null,
            'delivered_at' => null,
            'error_message' => null,
        ]);
    }

    public function delivered(): static
    {
        $sent_at = $this->faker->dateTimeBetween('-1 month');
        return $this->state(fn (array $attributes) => [
            'status' => 'delivered',
            'sent_at' => $sent_at,
            'delivered_at' => $this->faker->dateTimeBetween($sent_at),
            'error_message' => null,
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
            'error_message' => $this->faker->sentence(),
            'sent_at' => null,
            'delivered_at' => null,
        ]);
    }
}
