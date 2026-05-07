<?php

namespace Database\Factories;

use App\Models\JobApplication;
use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\JobVacancy;
use App\Models\User;
use App\Models\Resume;

/**
 * @extends Factory<JobApplication>
 */
class JobApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'job_vacancy_id' => JobVacancy::factory(),
            'user_id' => User::factory(),
            'resume_id' => Resume::factory(),
            'status' => fake()->randomElement(['Pending', 'Accepted', 'Rejected']),
            'ai_generated_score' => fake()->randomFloat(2, 40, 98),
            'ai_generated_feedback' => fake()->paragraphs(2, true),
        ];
    }
}
