<?php

namespace Database\Factories;

use App\Models\SmsGroup;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmsGroupFactory extends Factory
{
    protected $model = SmsGroup::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(),
            'created_by' => User::factory(),
            'created_at' => $this->faker->dateTimeBetween('-1 month'),
            'updated_at' => $this->faker->dateTimeBetween('-1 month'),
        ];
    }
}
