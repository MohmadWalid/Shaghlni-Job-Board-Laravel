<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'Frontend Development', 'Backend Development', 'Full Stack Development',
                'DevOps Engineering', 'Mobile Development', 'Cloud Engineering',
                'Software Architecture', 'Data Science', 'Machine Learning',
                'Cyber Security', 'UI/UX Design', 'Project Management'
            ]),
        ];
    }
}
