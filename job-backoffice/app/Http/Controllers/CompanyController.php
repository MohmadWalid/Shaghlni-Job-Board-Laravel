<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyCreateRequest;
use App\Http\Requests\CompanyUpdateRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\DTOs\CreateCompanyDTO;
use App\DTOs\CreateUserDTO;

class CompanyController extends Controller
{
    public $industries = [
        'Information Technology',
        'Healthcare',
        'Education',
        'Finance',
        'Engineering',
        'Hospitality',
        'Construction',
        'Retail',
    ];
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Start the query with the latest active companies.
        $query = Company::latest();

        // Conditionally apply the onlyTrashed() scope if 'archived' is 'true'
        if ($request->input('archived') == 'true') {
            $query->onlyTrashed();
        }

        // Paginate the results with 10 items per page and 1 link on each side
        $companies = $query->paginate(10)->onEachSide(1);

        // Pass to view
        return view('company.index')->with('companies', $companies);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $industries = $this->industries;

        return view('company.create', compact('industries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CompanyCreateRequest $request)
    {
        $companyDTO = CreateCompanyDTO::fromRequest($request);
        $ownerDTO = CreateUserDTO::fromRequest($request);

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

        return redirect()->route('companies.index')->with('success', 'Company created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company = null)
    {
        // For company owners accessing /my-company (no ID in URL),
        // we need to manually fetch their company
        if (Auth::user()->role === 'company-owner') {
            $company = Company::where('owner_id', Auth::id())->firstOrFail();
        }

        // At this point:
        // - Admin: $company = Company from route binding (e.g., Company #5)
        // - Owner: $company = Their own company from database query

        // Check if current user can view this specific company
        Gate::authorize('view', $company);

        $company->load(['job_vacancies', 'job_applications']);

        return view('company.show', compact('company'));
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company = null)
    {
        if (Auth::user()->role === 'company-owner') {
            $company = Company::where('owner_id', Auth::id())->firstOrFail();
        }

        Gate::authorize('update', $company);

        $industries = $this->industries;

        return view('company.edit', compact('company', 'industries'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyUpdateRequest $request, Company $company = null)
    {
        if (Auth::user()->role === 'company-owner') {
            $company = Company::where('owner_id', Auth::id())->firstOrFail();
        }

        Gate::authorize('update', $company);

        $validated = $request->validated();

        DB::transaction(function () use ($company, $validated) {
            // Update company
            $company->update([
                'name' => $validated['name'],
                'address' => $validated['address'],
                'industry' => $validated['industry'],
                'website' => $validated['website'],
            ]);

            // Update owner (name and/or password only - email is not editable)
            $ownerData = [];

            if (isset($validated['owner_name']))
                $ownerData['name'] = $validated['owner_name'];


            if (!empty($validated['owner_password']))
                $ownerData['password'] = Hash::make($validated['owner_password']);


            // Only update if there's data to update
            if (!empty($ownerData))
                $company->owner->update($ownerData);
        });

        // Redirect based on where the user came from
        $redirectTo = $request->input('redirect', 'index');

        if ($redirectTo === 'show') {
            if (Auth::user()->role === 'admin') {
                return redirect()->route('companies.show', $company)
                    ->with('success', 'Company updated successfully!');
            } else {
                return redirect()->route('my-company.show')
                    ->with('success', 'Company updated successfully!');
            }
        } else {
            return redirect()->route('companies.index')
                ->with('success', 'Company updated successfully!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        Gate::authorize('delete', $company);

        $company->delete();
        return redirect()->route('companies.index')->with('success', 'Company archived successfully!');
    }

    public function restore(Company $company)
    {
        Gate::defineauthorize('restore', $company);
        $company->restore();
        return redirect()->route('companies.index', ['archived' => 'true'])->with('success', 'Company restored successfully!');
    }
}
