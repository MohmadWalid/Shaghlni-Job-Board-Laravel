<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{
    /**
     * Display a listing of the user's job applications.
     */
    public function index(Request $request)
    {
        // 1. Start with user's applications
        $query = JobApplication::with('job_vacancy.company')
            ->where('user_id', Auth::id());

        // 2. Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->whereHas('job_vacancy', function ($q) use ($request) {
                $q->where('type', $request->type);
            });
        }

        // 4. Paginate and sort
        $applications = $query->latest()->paginate(10)->withQueryString();

        // 5. Calculate stats
        $statusCounts = JobApplication::where('user_id', Auth::id())
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('job-applications.index', compact('applications', 'statusCounts'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(JobApplication $jobApplications)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobApplication $jobApplications)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JobApplication $jobApplications)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobApplication $jobApplications)
    {
        //
    }
}
