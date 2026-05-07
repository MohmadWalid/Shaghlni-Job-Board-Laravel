<?php

namespace Database\Factories;

use App\Models\JobVacancy;
use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Company;

/**
 * @extends Factory<JobVacancy>
 */
class JobVacancyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->jobTitle(),
            'description' => fake()->paragraphs(3, true),
            'location' => fake()->city() . ', ' . fake()->country(),
            'type' => fake()->randomElement(['full-time', 'contract', 'remote', 'hybrid']),
            'salary' => fake()->numberBetween(3000, 15000),
            'required_skills' => json_encode(fake()->words(5)),
            'company_id' => Company::factory(),
            'viewCount' => fake()->numberBetween(0, 1000),
        ];
    }
}
