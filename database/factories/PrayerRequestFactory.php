<?php

namespace Database\Factories;

use App\Models\PrayerRequest;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrayerRequestFactory extends Factory
{
    protected $model = PrayerRequest::class;

    public function definition(): array
    {
        $status = $this->faker->randomElement(['submitted', 'in_prayer', 'answered', 'archived']);
        $is_anonymous = $this->faker->boolean(20);
        
        return [
            'member_id' => $this->faker->boolean(80) ? Member::factory() : null,
            'requester_name' => $is_anonymous ? null : $this->faker->name(),
            'requester_email' => $is_anonymous ? null : $this->faker->safeEmail(),
            'requester_phone' => $is_anonymous ? null : $this->faker->phoneNumber(),
            'request_content' => $this->faker->paragraph(),
            'status' => $status,
            'is_public' => $this->faker->boolean(70),
            'is_anonymous' => $is_anonymous,
            'answer_date' => $status === 'answered' ? $this->faker->dateTimeBetween('-1 month') : null,
            'answer_notes' => $status === 'answered' ? $this->faker->paragraph() : null,
            'created_at' => $this->faker->dateTimeBetween('-1 month'),
            'updated_at' => $this->faker->dateTimeBetween('-1 month'),
        ];
    }

    public function answered(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'answered',
            'answer_date' => $this->faker->dateTimeBetween('-1 month'),
            'answer_notes' => $this->faker->paragraph(),
        ]);
    }

    public function anonymous(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_anonymous' => true,
            'requester_name' => null,
            'requester_email' => null,
            'requester_phone' => null,
        ]);
    }

    public function public(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_public' => true,
        ]);
    }
}
