<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobApplicationRequest;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\Resume;
use App\Services\ApplicationEvaluatorService;
use App\Services\ResumeParserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class JobVacancyController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(JobVacancy $jobVacancy)
    {
        return view('job-vacancies.show', compact('jobVacancy'));
    }

    /**
     * Show the application form page.
     **/
    public function apply(JobVacancy $jobVacancy)
    {

        $resumes = Auth::user()->resumes()->orderBy('updated_at', 'desc')->get();

        return view('job-vacancies.apply', compact('jobVacancy', 'resumes'));
    }

    /**
     * Store a new job application.
     *
     * Services are injected via method injection (not constructor) to avoid
     * breaking other routes if a service dependency is unavailable.
     */
    public function storeApplication(
        StoreJobApplicationRequest $request,
        JobVacancy $jobVacancy,
        ResumeParserService $resumeParser,
        ApplicationEvaluatorService $evaluator
    ) {
        // 1. Check if user already applied to this vacancy
        $existingApplication = JobApplication::where('job_vacancy_id', $jobVacancy->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingApplication) {
            return redirect()
                ->route('job-vacancies.show', $jobVacancy)
                ->with('error', 'You have already applied to this job.');
        }

        // 2. Resolve the resume
        try {
            if ($request->resume_option === 'existing') {
                $resume = Resume::where('id', $request->existing_resume_id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();
            } else {
                $resume = $this->uploadNewResume($request->file('resume'), $resumeParser);
            }
        } catch (\Throwable $e) {
            Log::error('Resume processing failed', [
                'user_id' => Auth::id(),
                'job_vacancy_id' => $jobVacancy->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->withErrors(['resume' => 'Failed to process your resume: ' . $e->getMessage()])
                ->withInput();
        }

        // 3. Create the application (score and feedback will be generated asynchronously)
        $application = JobApplication::create([
            'job_vacancy_id'        => $jobVacancy->id,
            'resume_id'             => $resume->id,
            'user_id'               => Auth::id(),
            'status'                => 'Pending',
            'ai_generated_score'    => 0,
            'ai_generated_feedback' => ' ',
        ]);

        // 4. Dispatch the background job to evaluate the application
        \App\Jobs\EvaluateApplicationJob::dispatch($application);

        return redirect()
            ->route('job-applications.index')
            ->with('success', 'Application submitted successfully! Our AI is currently evaluating your resume.');
    }

    /**
     * Parse a new PDF resume in-memory and persist only the structured data.
     * The original PDF file is discarded at the end of the request lifecycle.
     */
    private function uploadNewResume($file, ResumeParserService $resumeParser): Resume
    {
        $parsedData = $resumeParser->parse($file);

        $resume = Resume::create([
            'file_name'       => $file->getClientOriginalName(),
            'file_url'        => 'N/A',
            'user_id'         => Auth::id(),
            'contact_details' => $parsedData['contact_details'],
            'summary'         => $parsedData['summary'],
            'skills'          => $parsedData['skills'],
            'experience'      => $parsedData['experience'],
            'education'       => $parsedData['education'],
        ]);

        Log::info('Resume parsed and stored', [
            'resume_id' => $resume->id,
            'user_id' => Auth::id(),
            'file_name' => $file->getClientOriginalName(),
        ]);

        return $resume;
    }
}
