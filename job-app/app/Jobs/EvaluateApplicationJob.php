<?php

namespace App\Jobs;

use App\Models\JobApplication;
use App\Services\ApplicationEvaluatorService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EvaluateApplicationJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120; // 2 minutes timeout for the API

    public function __construct(
        public JobApplication $application
    ) {}

    /**
     * Execute the job.
     */
    public function handle(ApplicationEvaluatorService $evaluator): void
    {
        // Load the related models
        $this->application->loadMissing(['resume', 'job_vacancy']);

        // Evaluate resume against job vacancy
        $evaluation = $evaluator->evaluate($this->application->resume, $this->application->job_vacancy);

        // Update the application with the result
        $this->application->update([
            'ai_generated_score'    => $evaluation['score'],
            'ai_generated_feedback' => $evaluation['feedback'],
        ]);
    }
}
