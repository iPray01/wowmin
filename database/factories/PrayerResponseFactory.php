<?php

namespace Database\Factories;

use App\Models\PrayerResponse;
use App\Models\PrayerRequest;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrayerResponseFactory extends Factory
{
    protected $model = PrayerResponse::class;

    public function definition(): array
    {
        return [
            'prayer_request_id' => PrayerRequest::factory(),
            'member_id' => Member::factory(),
            'response_content' => $this->faker->paragraph(),
            'is_private' => $this->faker->boolean(80),
            'created_at' => $this->faker->dateTimeBetween('-1 month'),
            'updated_at' => $this->faker->dateTimeBetween('-1 month'),
        ];
    }

    public function private(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_private' => true,
        ]);
    }

    public function public(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_private' => false,
        ]);
    }
}
