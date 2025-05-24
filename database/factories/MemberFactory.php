<?php

namespace Database\Factories;

use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemberFactory extends Factory
{
    protected $model = Member::class;

    public function definition(): array
    {
        $gender = $this->faker->randomElement(['male', 'female']);
        $maritalStatus = $this->faker->randomElement(['single', 'married', 'divorced', 'widowed']);

        return [
            'first_name' => $this->faker->firstName($gender),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'date_of_birth' => $this->faker->dateTimeBetween('-80 years', '-18 years'),
            'gender' => $gender,
            'marital_status' => $maritalStatus,
            'profile_photo' => null,
            'emergency_contact_name' => $this->faker->name(),
            'emergency_contact_phone' => $this->faker->phoneNumber(),
            'emergency_contact_relationship' => $this->faker->randomElement(['spouse', 'parent', 'sibling', 'child', 'friend']),
            'custom_fields' => null,
            'membership_status' => $this->faker->randomElement(['visitor', 'regular', 'member', 'leader']),
            'membership_date' => $this->faker->optional(0.7)->dateTimeBetween('-10 years'),
            'baptism_date' => $this->faker->optional(0.6)->dateTimeBetween('-10 years'),
            'is_active' => $this->faker->boolean(80),
            'created_at' => $this->faker->dateTimeBetween('-1 year'),
            'updated_at' => $this->faker->dateTimeBetween('-1 month'),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    public function member(): static
    {
        return $this->state(fn (array $attributes) => [
            'membership_status' => 'member',
            'membership_date' => $this->faker->dateTimeBetween('-10 years'),
            'baptism_date' => $this->faker->dateTimeBetween('-10 years'),
            'is_active' => true,
        ]);
    }

    public function leader(): static
    {
        return $this->state(fn (array $attributes) => [
            'membership_status' => 'leader',
            'membership_date' => $this->faker->dateTimeBetween('-10 years'),
            'baptism_date' => $this->faker->dateTimeBetween('-10 years'),
            'is_active' => true,
        ]);
    }
}
