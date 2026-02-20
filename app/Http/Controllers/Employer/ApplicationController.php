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
    public function updateStatus(Request $request, Application $application)
    {
        $user = Auth::user();

        // Only the job owner (employer) can update status
        if ($application->job->employer_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => 'required|in:reviewed,shortlisted,interview_scheduled,interviewed,accepted,rejected',
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        // Business rule: Cannot manually set to interview_scheduled
        if ($validated['status'] === 'interview_scheduled') {
            return back()->with('error', 'Use the "Schedule Interview" button to set this status.');
        }

        // Update application status with new migration fields
        $updateData = [
            'application_status' => $validated['status'],
            'status_updated_at' => now(),
        ];

        // Add rejection reason if status is rejected
        if ($validated['status'] === 'rejected' && !empty($validated['rejection_reason'])) {
            $updateData['rejection_reason'] = $validated['rejection_reason'];
        }

        $application->update($updateData);

        // Job seeker user
        $jobSeekerUser = $application->jobSeeker->user;

        // Notification content based on status
        $statusMessages = [
            'reviewed' => ['title' => 'Application Under Review 📋', 'icon' => 'bi-eye-fill', 'color' => 'info'],
            'shortlisted' => ['title' => 'Application Shortlisted! 🎯', 'icon' => 'bi-star-fill', 'color' => 'warning'],
            'interviewed' => ['title' => 'Interview Completed ✅', 'icon' => 'bi-check-circle-fill', 'color' => 'success'],
            'accepted' => ['title' => 'Application Accepted 🎉', 'icon' => 'bi-check-circle-fill', 'color' => 'success'],
            'rejected' => ['title' => 'Application Rejected ❌', 'icon' => 'bi-x-circle-fill', 'color' => 'danger'],
        ];

        $statusInfo = $statusMessages[$validated['status']];

        // Build message
        $messages = [
            'reviewed' => "Your application for {$application->job->job_title} is under review.",
            'shortlisted' => "Congratulations! You've been shortlisted for {$application->job->job_title}. An interview may be scheduled soon.",
            'interviewed' => "Your interview for {$application->job->job_title} has been completed.",
            'accepted' => "Congratulations! Your application for {$application->job->job_title} has been accepted!",
            'rejected' => "Your application for {$application->job->job_title} has been rejected.",
        ];

        $message = $messages[$validated['status']];
        
        if ($validated['status'] === 'rejected' && !empty($validated['rejection_reason'])) {
            $message .= " Reason: {$validated['rejection_reason']}";
        }

        // Create notification for job seeker
        Notification::create([
            'user_id' => $jobSeekerUser->id,
            'type' => 'application_status_update',
            'title' => $statusInfo['title'],
            'message' => $message,
            'data' => [
                'job_id' => $application->job->id,
                'application_id' => $application->id,
                'status' => $validated['status'],
                'employer_name' => $user->name,
                'job_title' => $application->job->job_title,
                'rejection_reason' => $validated['rejection_reason'] ?? null,
            ],
            'action_url' => route('users.applications', [
                'highlight' => $application->job->id,
            ]),
            'icon' => $statusInfo['icon'],
            'color' => $statusInfo['color'],
        ]);

        $successMessages = [
            'reviewed' => 'Application marked as reviewed.',
            'shortlisted' => 'Application shortlisted successfully!',
            'interviewed' => 'Application marked as interviewed.',
            'accepted' => 'Application accepted successfully!',
            'rejected' => 'Application rejected.',
        ];

        return back()->with('success', $successMessages[$validated['status']]);
    }
}