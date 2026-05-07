<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\Resume;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed the root Admin
        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Seed data to test With
        $job_data = json_decode(file_get_contents(database_path('data/job_data.json')), true);

        // Create Job Categories
        foreach ($job_data['jobCategories'] as $category) {
            Category::firstOrCreate(['name' => $category]);
        }

        // Create Companies with Owners
        foreach ($job_data['companies'] as $company) {
            // Create job data company owner
            $companyOwner = User::firstOrCreate(
                ['email' => fake()->unique()->safeEmail()],
                [
                    'name' => fake()->name(),
                    'password' => Hash::make('12345678'),
                    'role' => 'company-owner',
                    'email_verified_at' => now(),
                ]
            );

            // Create company
            Company::firstOrCreate(
                ['name' => $company['name']],
                [
                    'address' => $company['address'],
                    'industry' => $company['industry'],
                    'website' => $company['website'],
                    'owner_id' => $companyOwner->id,
                ]
            );
        }

        // Create Job Vacancies and link with Categories
        foreach ($job_data['jobVacancies'] as $vacancy) {
            $company = Company::where('name', $vacancy['company'])->firstOrFail();

            $job = JobVacancy::create([
                'title' => $vacancy['title'],
                'description' => $vacancy['description'],
                'location' => $vacancy['location'],
                'type' => $vacancy['type'],
                'salary' => $vacancy['salary'],
                'required_skills' => json_encode($vacancy['technologies']),
                'company_id' => $company->id,
            ]);

            // Attach multiple categories
            foreach ($vacancy['category'] as $categoryName) {
                $category = Category::firstWhere('name', $categoryName);
                $job->categories()->attach($category->id);
            }
        }

        //===================================================
        // Create Job application
        $job_applications = json_decode(file_get_contents(database_path('data/job_applications.json')), true);
        foreach ($job_applications['jobApplications'] as $application) {
            // Get Random Job Vacncy
            $job_vacancy = JobVacancy::inRandomOrder()->first();

            // Create applicant (job-seeker)
            $applicant = User::firstOrCreate([
                'email' => fake()->unique()->safeEmail(),
            ], [
                'name' => fake()->name(),
                'password' => Hash::make('12345678'),
                'role' => 'job-seeker',
                'email_verified_at' => now(),
            ]);

            // Create Resume
            $resume = Resume::firstOrCreate(
                [
                    'file_name'        => $application['resume']['filename'],
                    'file_url'         => $application['resume']['fileUri'],
                    'contact_details'  => $application['resume']['contactDetails'],
                    'summary'          => $application['resume']['summary'],
                    'skills'           => $application['resume']['skills'],
                    'experience'       => $application['resume']['experience'],
                    'education'        => $application['resume']['education'],
                    'user_id' => $applicant->id,
                ]
            );

            // Create job application
            JobApplication::create([
                'job_vacancy_id' => $job_vacancy->id,
                'user_id' => $applicant->id,
                'resume_id' => $resume->id,
                'status' => $application['status'],
                'ai_generated_score' => $application['aiGeneratedScore'],
                'ai_generated_feedback' => $application['aiGeneratedFeedback'],
            ]);
        }

        // ===================================================
        // RANDOM LARGE SCALE DATA (Factories)
        // ===================================================
        
        // 1. Create 10 more companies with owners
        Company::factory()->count(10)->create();

        // 2. Create 30 more job vacancies linked to random companies
        $all_companies = Company::all();
        $categories = Category::all();

        JobVacancy::factory()->count(30)->create([
            'company_id' => fn() => $all_companies->random()->id
        ])->each(function ($job) use ($categories) {
            $job->categories()->attach($categories->random(rand(1, 3))->pluck('id'));
        });

        // 3. Create 100 more job applications
        $all_jobs = JobVacancy::all();
        
        // We create users and resumes first, then applications
        User::factory()->count(50)->create()->each(function ($user) use ($all_jobs) {
            // Each user has 1-2 resumes
            $resumes = Resume::factory()->count(rand(1, 2))->create([
                'user_id' => $user->id
            ]);

            // Each user applies to 1-3 jobs
            foreach($all_jobs->random(rand(1, 3)) as $job) {
                JobApplication::factory()->create([
                    'job_vacancy_id' => $job->id,
                    'user_id' => $user->id,
                    'resume_id' => $resumes->random()->id,
                ]);
            }
        });
    }
}
