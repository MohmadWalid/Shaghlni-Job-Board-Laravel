<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobApplication extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'job_applications';

    protected $fillable = [
        'ai_generated_score',
        'ai_generated_feedback',
        'status',
        'user_id',
        'job_vacancy_id',
        'resume_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function job_vacancy(): BelongsTo
    {
        return $this->belongsTo(JobVacancy::class, 'job_vacancy_id', 'id');
    }

    public function resume(): BelongsTo
    {
        return $this->belongsTo(Resume::class, 'resume_id', 'id');
    }

    /**
     * Convert ai_generated_score from /100 to /10 (1 decimal place).
     * e.g. 82 -> 8.2
     */
    protected function scoreOutOf10(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->ai_generated_score !== null
                ? round($this->ai_generated_score / 10, 1)
                : null,
        );
    }

    /**
     * Parse ai_generated_feedback into an array of clean bullet-point strings.
     * Handles newline-separated text and lines starting with -, *, or numbers.
     */
    protected function feedbackBulletPoints(): Attribute
    {
        return Attribute::make(
            get: function () {
                $raw = $this->ai_generated_feedback;
                if (empty($raw)) {
                    return [];
                }

                $lines = explode("\n", str_replace("\r\n", "\n", $raw));
                $points = [];
                foreach ($lines as $line) {
                    // Strip leading bullet markers: -, *, or numbered list (1. / 1))
                    $clean = preg_replace('/^\s*[-*]\s*/', '', $line);
                    $clean = preg_replace('/^\s*\d+[.)]\s*/', '', $clean);
                    $clean = trim($clean);
                    if ($clean !== '') {
                        $points[] = $clean;
                    }
                }

                return $points;
            },
        );
    }

    /**
     * Resolve a Tailwind colour key based on the score.
     * emerald >= 7 | amber >= 5 | rose < 5 | indigo (no score)
     */
    protected function scoreColor(): Attribute
    {
        return Attribute::make(
            get: function () {
                $score = $this->score_out_of10;
                if ($score === null) return 'indigo';
                if ($score >= 7)    return 'emerald';
                if ($score >= 5)    return 'amber';
                return 'rose';
            },
        );
    }
}
