<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 'admin')
            $analaticsData = $this->adminDashboard();
        elseif (Auth::user()->role == 'company-owner')
            $analaticsData = $this->companyOwnerDashboard();

        return view('dashboard.index', compact('analaticsData'));
    }

    public function adminDashboard()
    {
        $analaticsData = [];
        // 1. Get the NUMBER active users in Last 30 days (job-seeker Only)
        $activeUsers = User::where('role', 'job-seeker')
            ->where('last_login_at', '>=', Carbon::now()->subDays(30))
            ->count();
        // 2. Get the NUMBER of (Active) job vacancies
        $activeJobVacancies = JobVacancy::wherenull('deleted_at')->count();
        // 3. Get the NUMBER of (Active) job application
        $activeJobApplication = JobApplication::wherenull('deleted_at')->count();


        // Most Applied Jobs
        $mostAppliedJobs = JobVacancy::withCount('job_applications as totalCount')
            ->whereNull('deleted_at')
            ->orderByDesc('totalCount')
            ->limit(5)
            ->get();

        // Most Conversion Rates
        $conversionRates = JobVacancy::withCount('job_applications as totalCount')
            ->having('totalCount', '>', 0)
            ->limit(5)
            ->orderByDesc('totalCount')
            ->get()
            ->map(function ($job) {
                if ($job->viewCount)
                    $job->conversionRate = $job->totalCount / $job->viewCount * 100;
                return $job;
            });

        $analaticsData = [
            'activeUsers' => $activeUsers,
            'activeJobVacancies' => $activeJobVacancies,
            'activeJobApplication' => $activeJobApplication,
            'conversionRates' => $conversionRates,
            'mostAppliedJobs' => $mostAppliedJobs,
        ];

        return $analaticsData;
    }
    public function companyOwnerDashboard()
    {
        // Get only the company ID (we don't need the full company model yet)
        $companyId = Auth::user()->companies()->value('id'); // fastest: just the ID

        $activeUsers = User::where('role', 'job-seeker')
            ->where('last_login_at', '>=', Carbon::now()->subDays(30))
            ->whereHas('job_applications.job_vacancy', function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->count();

        $activeJobApplication = JobApplication::whereHas('job_vacancy', function ($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })
            ->whereNull('deleted_at')
            ->count();

        $activeJobVacancies = JobVacancy::where('company_id', $companyId)
            ->whereNull('deleted_at')
            ->count();

        // Most Applied Jobs For this Company
        $mostAppliedJobs = JobVacancy::withCount('job_applications as totalCount')
            ->where('company_id', $companyId)
            ->whereNull('deleted_at')
            ->orderByDesc('totalCount')
            ->limit(5)
            ->get();

        // Most Conversion Rates
        $conversionRates = JobVacancy::withCount('job_applications as totalCount')
            ->where('company_id', $companyId)
            ->having('totalCount', '>', 0)
            ->limit(5)
            ->orderByDesc('totalCount')
            ->get()
            ->map(function ($job) {
                if ($job->viewCount)
                    $job->conversionRate = $job->totalCount / $job->viewCount * 100;
                return $job;
            });

        $analaticsData = [
            'activeUsers' => $activeUsers,
            'activeJobVacancies' => $activeJobVacancies,
            'activeJobApplication' => $activeJobApplication,
            'conversionRates' => $conversionRates,
            'mostAppliedJobs' => $mostAppliedJobs,
        ];

        return $analaticsData;
    }
}
