<?php

namespace Database\Factories;

use App\Models\Resume;
use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\User;

/**
 * @extends Factory<Resume>
 */
class ResumeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'file_name' => fake()->word() . '.pdf',
            'file_url' => fake()->url(),
            'contact_details' => json_encode([
                'phone' => fake()->phoneNumber(),
                'address' => fake()->address(),
                'linkedin' => 'linkedin.com/in/' . fake()->userName(),
            ]),
            'summary' => fake()->paragraph(),
            'skills' => json_encode(fake()->words(8)),
            'experience' => json_encode([
                [
                    'company' => fake()->company(),
                    'role' => fake()->jobTitle(),
                    'years' => fake()->numberBetween(1, 10),
                    'description' => fake()->sentence()
                ]
            ]),
            'education' => json_encode([
                [
                    'school' => fake()->company() . ' University',
                    'degree' => fake()->randomElement(['BSc', 'MSc', 'PhD']) . ' in Computer Science',
                    'year' => fake()->year()
                ]
            ]),
            'user_id' => User::factory(),
        ];
    }
}
