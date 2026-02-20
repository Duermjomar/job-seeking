<?php
// Job.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'employer_id',
        'job_title',
        'job_description',
        'job_type',
        'salary',
        'requirements',
        'location',
        'status',
    ];

    // REMOVED: protected $with = ['templates']
    // Reason: auto-loading templates on every Job query causes unnecessary
    // overhead on listing pages, dashboards, and admin views that don't need them.
    // Instead, eager-load explicitly where needed:
    //   Job::with('templates')->find($id)   ← in show/apply controller
    //   $job->load('templates')             ← when already have the model

    public function employer()
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function templates()
    {
        return $this->hasMany(JobApplicationTemplate::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Scopes
    |--------------------------------------------------------------------------
    */

    /** Only open jobs */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    /** Only closed jobs */
    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }
}