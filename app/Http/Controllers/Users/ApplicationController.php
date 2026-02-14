<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;
use App\Models\Job;
use App\Models\Notification;
use App\Http\Controllers\Controller;

class ApplicationController extends Controller
{
    public function store(Request $request, Job $job)
    {
        $user = Auth::user();
        $jobSeeker = $user->jobSeeker;

        if (!$jobSeeker) {
            return back()->with('error', 'Job seeker profile not found.');
        }

        // Check if profile has resume
        if (!$jobSeeker->resume) {
            return back()->with('error', 'Please upload your resume in your profile before applying.');
        }

        // Prevent duplicate application
        $alreadyApplied = Application::where('job_id', $job->id)
            ->where('job_seeker_id', $jobSeeker->id)
            ->exists();

        if ($alreadyApplied) {
            return back()->with('error', 'You already applied for this job.');
        }

        // Validate only application letter (optional)
        $request->validate([
            'application_letter' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        // Create application
        $application = Application::create([
            'job_id' => $job->id,
            'job_seeker_id' => $jobSeeker->id,
            'application_status' => 'pending',
            'applied_at' => now(),
        ]);

        // Handle Application Letter Upload (if provided)
        if ($request->hasFile('application_letter')) {
            $letterFile = $request->file('application_letter');
            
            // Get EXACT original filename
            $originalName = $letterFile->getClientOriginalName();
            
            // Store the file with EXACT original filename
            $letterPath = $letterFile->storeAs('applications/letters', $originalName, 'public');

            $application->files()->create([
                'file_path' => $letterPath,
                'file_type' => 'application_letter',
                'original_name' => $originalName,
                'mime_type' => $letterFile->getMimeType(),
                'file_size' => $letterFile->getSize(),
            ]);
        }

        // Create notification for Job Seeker (applicant)
        Notification::create([
            'user_id' => $user->id,
            'type' => 'application_submitted',
            'title' => 'Application Submitted!',
            'message' => "Your application for {$job->job_title} has been submitted successfully.",
            'data' => json_encode([
                'job_id' => $job->id,
                'application_id' => $application->id,
                'job_title' => $job->job_title,
            ]),
            'action_url' => route('users.applications', ['highlight' => $job->id,]),
            'icon' => 'bi-send-fill',
            'color' => 'success',
        ]);

        // Create notification for Employer
        Notification::create([
            'user_id' => $job->employer_id,
            'type' => 'new_application',
            'title' => 'New Application Received!',
            'message' => "{$user->name} has applied for your job: {$job->job_title}",
            'data' => [
                'job_id' => $job->id,
                'application_id' => $application->id,
                'job_seeker_id' => $jobSeeker->id,
                'job_seeker_name' => $user->name,
                'job_title' => $job->job_title,
            ],
            'action_url' => route('employer.jobs.applicants', [
                'job' => $job->id,
                'highlight' => $application->id,
            ]),
            'icon' => 'bi-person-plus-fill',
            'color' => 'primary',
        ]);

        return back()->with('success', 'Application submitted successfully!');
    }
   
    public function trackApplications()
    {
        $user = Auth::user();
        $jobSeeker = $user->jobSeeker;

        $totalApplications = 0;
        $pendingApplications = 0;
        $acceptedApplications = 0;
        $rejectedApplications = 0;
        $applications = collect();

        if ($jobSeeker) {
            $applications = $jobSeeker->applications()
                ->with(['job', 'files'])
                ->latest()
                ->get();

            $totalApplications = $applications->count();
            $pendingApplications = $applications->where('application_status', 'pending')->count();
            $acceptedApplications = $applications->where('application_status', 'accepted')->count();
            $rejectedApplications = $applications->where('application_status', 'rejected')->count();
        }

        return view('Users.trackApplications', compact(
            'applications',
            'totalApplications',
            'pendingApplications',
            'acceptedApplications',
            'rejectedApplications'
        ));
    }
}