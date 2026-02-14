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