<?php

namespace App\Policies;

use App\Models\JobVacancy;
use App\Models\User;

class JobVacancyPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, JobVacancy $jobVacancy): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->role === 'company-owner'
            && $jobVacancy->company_id === $user->companies()->first()?->id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, JobVacancy $jobVacancy): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->role === 'company-owner'
            && $jobVacancy->company_id === $user->companies()->first()?->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, JobVacancy $jobVacancy): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->role === 'company-owner'
            && $jobVacancy->company_id === $user->companies()->first()?->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, JobVacancy $jobVacancy): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->role === 'company-owner'
            && $jobVacancy->company_id === $user->companies()->first()?->id;
    }
}
