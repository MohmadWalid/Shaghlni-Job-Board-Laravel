<?php

namespace App\Services;

use App\DTOs\CreateCompanyDTO;
use App\DTOs\CreateUserDTO;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CompanyService
{
    public function createCompanyWithOwner(CreateCompanyDTO $companyDTO, CreateUserDTO $ownerDTO): void
    {
        DB::transaction(function () use ($companyDTO, $ownerDTO) {
            // Create owner
            $owner = User::create([
                'name' => $ownerDTO->name,
                'email' => $ownerDTO->email,
                'password' => Hash::make($ownerDTO->password),
                'role' => $ownerDTO->role,
            ]);

            // Create company and link it to the new owner
            Company::create([
                'name' => $companyDTO->name,
                'address' => $companyDTO->address,
                'industry' => $companyDTO->industry,
                'website' => $companyDTO->website,
                'owner_id' => $owner->id,
            ]);
        });
    }
}