<?php
// Application.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'job_seeker_id',
        'application_status',
        'rejection_reason',
        'reapply_count',
        'status_updated_at',
        'applied_at',
    ];

    protected $casts = [
        'applied_at'        => 'datetime',
        'status_updated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Status Constants — matches ALL enum values in the migration
    |--------------------------------------------------------------------------
    */
    const STATUS_PENDING              = 'pending';
    const STATUS_REVIEWED             = 'reviewed';
    const STATUS_SHORTLISTED          = 'shortlisted';
    const STATUS_INTERVIEW_SCHEDULED  = 'interview_scheduled';
    const STATUS_INTERVIEWED          = 'interviewed';
    const STATUS_ACCEPTED             = 'accepted';
    const STATUS_REJECTED             = 'rejected';

    // All valid statuses in pipeline order — useful for validation & comparisons
    const ALL_STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_REVIEWED,
        self::STATUS_SHORTLISTED,
        self::STATUS_INTERVIEW_SCHEDULED,
        self::STATUS_INTERVIEWED,
        self::STATUS_ACCEPTED,
        self::STATUS_REJECTED,
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function jobSeeker()
    {
        return $this->belongsTo(JobSeeker::class);
    }

    public function files()
    {
        return $this->hasMany(ApplicationFile::class);
    }

    public function interview()
    {
        return $this->hasOne(Interview::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */
    public function isPending(): bool
    {
        return $this->application_status === self::STATUS_PENDING;
    }

    public function isReviewed(): bool
    {
        return $this->application_status === self::STATUS_REVIEWED;
    }

    public function isShortlisted(): bool
    {
        return $this->application_status === self::STATUS_SHORTLISTED;
    }

    public function isInterviewScheduled(): bool
    {
        return $this->application_status === self::STATUS_INTERVIEW_SCHEDULED;
    }

    public function isInterviewed(): bool
    {
        return $this->application_status === self::STATUS_INTERVIEWED;
    }

    public function isAccepted(): bool
    {
        return $this->application_status === self::STATUS_ACCEPTED;
    }

    public function isRejected(): bool
    {
        return $this->application_status === self::STATUS_REJECTED;
    }

    /**
     * Uses the already-loaded relation if available — avoids extra query.
     */
    public function hasInterview(): bool
    {
        // If relation already loaded, use it — no extra DB hit
        if ($this->relationLoaded('interview')) {
            return $this->interview !== null;
        }

        return $this->interview()->exists();
    }

    /**
     * Only shortlisted applications without an existing interview can be scheduled.
     */
    public function canScheduleInterview(): bool
    {
        return $this->application_status === self::STATUS_SHORTLISTED
            && !$this->hasInterview();
    }

    /**
     * Job seeker can reapply only if rejected AND their profile was updated
     * after the rejection timestamp.
     */
    public function canReapply($profileUpdatedAt): bool
    {
        if (!$this->isRejected()) {
            return false;
        }

        if (!$profileUpdatedAt || !$this->status_updated_at) {
            return false;
        }

        return $profileUpdatedAt->gt($this->status_updated_at);
    }
}