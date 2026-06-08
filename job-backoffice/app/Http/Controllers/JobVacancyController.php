<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobVacancyCreateRequest;
use App\Http\Requests\JobVacancyUpdateRequest;
use App\Models\Category;
use App\Models\Company;
use App\Models\JobVacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobVacancyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Start the query with the latest active job-vacancies.
        $query = JobVacancy::with('company')->latest();

        if (Auth::user()->role == 'company-owner') {
            $query->where('company_id', Auth::user()->companies()->first()->id);
        }

        // Conditionally apply the onlyTrashed() scope if 'archived' is 'true'
        if ($request->input('archived') == 'true') {
            $query->onlyTrashed();
        }

        // Paginate the results with 10 items per page and 1 link on each side
        $jobVacancies = $query->paginate(10)->onEachSide(1);

        // Pass to view
        return view('job-vacancy.index', compact('jobVacancies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = Company::all();
        $categories = Category::all();

        return view('job-vacancy.create', compact('companies', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JobVacancyCreateRequest $request)
    {
        $jobVacancy = JobVacancy::create($request->validated());
        $jobVacancy->categories()->attach($request->category_ids);

        return redirect()->route('job-vacancies.index')->with('success', 'Job Vacancy created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $jobVacancy = JobVacancy::findOrFail($id);

        return view('job-vacancy.show', compact('jobVacancy'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $jobVacancy = JobVacancy::findOrFail($id);
        $companies = Company::all();
        $categories = Category::all();

        return view('job-vacancy.edit', compact('jobVacancy', 'companies', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JobVacancyUpdateRequest $request, string $id)
    {
        $jobVacancy = JobVacancy::findOrFail($id);
        $jobVacancy->update($request->validated());
        $jobVacancy->categories()->sync($request->category_ids);

        // Redirect based on where the user came from
        $redirectTo = $request->input('redirect', 'index');

        if ($redirectTo === 'show')
            return redirect()->route('job-vacancies.show', $id)->with('success', 'Job Vacancy updated successfully!');
        else
            return redirect()->route('job-vacancies.index')->with('success', 'Job Vacancy updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jobVacancy = JobVacancy::findOrFail($id);
        $jobVacancy->delete();
        return redirect()->route('job-vacancies.index')->with('success', 'Job Vacancy archived successfully!');
    }

    public function restore(string $id)
    {
        $jobVacancy = JobVacancy::withTrashed()->findOrFail($id);
        $jobVacancy->restore();
        return redirect()->route('job-vacancies.index', ['archived' => 'true'])->with('success', 'Job Vacancy restored successfully!');
    }
}
