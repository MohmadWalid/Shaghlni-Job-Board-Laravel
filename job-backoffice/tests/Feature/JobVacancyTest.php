<?php

use App\Models\Category;
use App\Models\Company;
use App\Models\JobVacancy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest cannot create job vacancy', function () {
    $response = $this->post('/job-vacancies', []);
    $response->assertRedirect('/login');
});

test('admin can create job vacancy for any company', function () {
    $admin = User::factory()->admin()->create();
    $company = Company::factory()->create();
    $category = Category::factory()->create();

    $response = $this->actingAs($admin)->post('/job-vacancies', [
        'title' => 'Admin Job Title test',
        'description' => 'This is a long description with at least fifty characters to satisfy validation.',
        'required_skills' => 'Laravel, PHP',
        'location' => 'Remote',
        'salary' => 100000,
        'type' => 'full-time',
        'company_id' => $company->id,
        'category_ids' => [$category->id],
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('job-vacancies.index'));
    $this->assertDatabaseHas('job_vacancies', [
        'title' => 'Admin Job Title test',
        'company_id' => $company->id,
    ]);
});

test('company owner can create job vacancy and it is forced to their own company', function () {
    $owner = User::factory()->companyOwner()->create();
    $ownerCompany = Company::factory()->create(['owner_id' => $owner->id]);
    $otherCompany = Company::factory()->create();
    $category = Category::factory()->create();

    $response = $this->actingAs($owner)->post('/job-vacancies', [
        'title' => 'Owner Job Title test',
        'description' => 'This is a long description with at least fifty characters to satisfy validation.',
        'required_skills' => 'Laravel, PHP',
        'location' => 'Remote',
        'salary' => 100000,
        'type' => 'full-time',
        'company_id' => $otherCompany->id, // Try to spoof company_id
        'category_ids' => [$category->id],
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('job-vacancies.index'));
    
    // Assert it was created for their own company, NOT the spoofed one
    $this->assertDatabaseHas('job_vacancies', [
        'title' => 'Owner Job Title test',
        'company_id' => $ownerCompany->id,
    ]);
    $this->assertDatabaseMissing('job_vacancies', [
        'title' => 'Owner Job Title test',
        'company_id' => $otherCompany->id,
    ]);
});

test('company owner cannot view, edit, update, or delete other company vacancies', function () {
    $owner = User::factory()->companyOwner()->create();
    $ownerCompany = Company::factory()->create(['owner_id' => $owner->id]);
    
    $otherOwner = User::factory()->companyOwner()->create();
    $otherCompany = Company::factory()->create(['owner_id' => $otherOwner->id]);
    $otherVacancy = JobVacancy::factory()->create(['company_id' => $otherCompany->id]);
    $category = Category::factory()->create();

    // View check
    $this->actingAs($owner)->get(route('job-vacancies.show', $otherVacancy->id))->assertForbidden();

    // Edit check
    $this->actingAs($owner)->get(route('job-vacancies.edit', $otherVacancy->id))->assertForbidden();

    // Update check
    $this->actingAs($owner)->put(route('job-vacancies.update', $otherVacancy->id), [
        'title' => 'Updated Title',
        'description' => 'This is a long description with at least fifty characters to satisfy validation.',
        'required_skills' => 'Laravel, PHP',
        'location' => 'Remote',
        'salary' => 100000,
        'type' => 'full-time',
        'company_id' => $otherCompany->id,
        'category_ids' => [$category->id],
    ])->assertForbidden();

    // Delete check
    $this->actingAs($owner)->delete(route('job-vacancies.destroy', $otherVacancy->id))->assertForbidden();
});

test('company owner can view, edit, update, and delete their own company vacancies', function () {
    $owner = User::factory()->companyOwner()->create();
    $ownerCompany = Company::factory()->create(['owner_id' => $owner->id]);
    $vacancy = JobVacancy::factory()->create(['company_id' => $ownerCompany->id]);
    $category = Category::factory()->create();

    // View check
    $this->actingAs($owner)->get(route('job-vacancies.show', $vacancy->id))->assertOk();

    // Edit check
    $this->actingAs($owner)->get(route('job-vacancies.edit', $vacancy->id))->assertOk();

    // Update check
    $this->actingAs($owner)->put(route('job-vacancies.update', $vacancy->id), [
        'title' => 'Updated Title',
        'description' => 'This is a long description with at least fifty characters to satisfy validation.',
        'required_skills' => 'Laravel, PHP',
        'location' => 'Remote',
        'salary' => 100000,
        'type' => 'full-time',
        'company_id' => $ownerCompany->id,
        'category_ids' => [$category->id],
    ])->assertRedirect();

    $this->assertDatabaseHas('job_vacancies', [
        'id' => $vacancy->id,
        'title' => 'Updated Title',
    ]);

    // Delete check
    $this->actingAs($owner)->delete(route('job-vacancies.destroy', $vacancy->id))->assertRedirect();
    $this->assertSoftDeleted('job_vacancies', [
        'id' => $vacancy->id,
    ]);
});
