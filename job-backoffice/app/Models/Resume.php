<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resume extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    protected $table = 'resumes';

    protected $fillable = [
        'file_name',
        'file_url',
        'contact_details',
        'summary',
        'skills',
        'experience',
        'education',
        'user_id'
    ];

    /**
     * Automatically decode JSON columns to PHP arrays when accessed.
     */
    protected $casts = [
        'skills'    => 'array',
        'education' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function job_applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }
}
