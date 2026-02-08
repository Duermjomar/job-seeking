<?php

namespace App\Http\Controllers\Employer;

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
            $letterPath = $letterFile->store('applications/letters', 'public');

            $application->files()->create([
                'file_path' => $letterPath,
                'file_type' => 'application_letter',
                'original_name' => $letterFile->getClientOriginalName(),
                'mime_type' => $letterFile->getMimeType(),
                'file_size' => $letterFile->getSize(),
            ]);
        }

        return back()->with('success', 'Application submitted successfully!');
    }



    public function updateStatus(Request $request, Application $application)
    {
        $user = Auth::user();

        // Only the job owner (employer) can update status
        if ($application->job->employer_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:accepted,rejected',
        ]);

        // Update application status
        $application->update([
            'application_status' => $request->status
        ]);

        // Job seeker user
        $jobSeekerUser = $application->jobSeeker->user;

        // Notification content based on status
        $statusText = $request->status === 'accepted' ? 'Accepted 🎉' : 'Rejected ❌';
        $icon = $request->status === 'accepted' ? 'bi-check-circle-fill' : 'bi-x-circle-fill';
        $color = $request->status === 'accepted' ? 'success' : 'danger';

        // Create notification for job seeker
        Notification::create([
            'user_id' => $jobSeekerUser->id,
            'type' => 'application_status_update',
            'title' => "Application {$statusText}",
            'message' => "Your application for {$application->job->job_title} has been {$request->status}.",
            'data' => [
                'job_id' => $application->job->id,
                'application_id' => $application->id,
                'status' => $request->status,
                'employer_name' => $user->name,
                'job_title' => $application->job->job_title,
            ],
            'action_url' => route('users.applications', [
                'highlight' => $application->job->id, 
            ]),
            'icon' => $icon,
            'color' => $color,
        ]);

        return back()->with('success', 'Application status updated.');
    }


}