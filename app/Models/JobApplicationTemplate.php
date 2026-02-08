<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplicationTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'file_name',
        'file_path',
        'file_type',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}