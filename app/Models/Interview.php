<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'scheduled_at',
        'interview_type',
        'location',
        'meeting_link',
        'notes',
        'status',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    /**
     * Get the application that owns the interview
     */
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * Check if interview is in the past
     */
    public function isPast()
    {
        return $this->scheduled_at->isPast();
    }

    /**
     * Check if interview is upcoming
     */
    public function isUpcoming()
    {
        return $this->scheduled_at->isFuture();
    }

    /**
     * Get days until interview
     */
    public function daysUntil()
    {
        return now()->diffInDays($this->scheduled_at, false);
    }

    /**
     * Get hours until interview
     */
    public function hoursUntil()
    {
        return now()->diffInHours($this->scheduled_at, false);
    }
}