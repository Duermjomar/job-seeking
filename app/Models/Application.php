<?php

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
        'applied_at',
    ];

    // Relationship to Job
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    // Relationship to JobSeeker
    public function jobSeeker()
    {
        return $this->belongsTo(\App\Models\JobSeeker::class);
    }

    public function files()
    {
        return $this->hasMany(ApplicationFile::class);
    }

}
