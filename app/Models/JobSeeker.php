<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobSeeker extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'birthdate',
        'gender',
        'resume',
        'profile_summary',
    ];

    /**
     * Link to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Applications made by this job seeker
     */
    public function applications()
    {
        return $this->hasMany(\App\Models\Application::class);
    }
}
