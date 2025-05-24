<?php

namespace Database\Factories;

use App\Models\SmsTemplate;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmsTemplateFactory extends Factory
{
    protected $model = SmsTemplate::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'content' => $this->faker->paragraph(),
            'description' => $this->faker->sentence(),
            'created_by' => User::factory(),
            'created_at' => $this->faker->dateTimeBetween('-1 month'),
            'updated_at' => $this->faker->dateTimeBetween('-1 month'),
        ];
    }
}
