<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Interview;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterviewController extends Controller
{
    /**
     * Schedule an interview for an application
     */
    public function scheduleInterview(Request $request, Application $application)
    {
        $user = Auth::user();

        // Authorization check
        if ($application->job->employer_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Business logic checks
        if ($application->application_status !== 'shortlisted') {
            return back()->with('error', 'Only shortlisted applications can have interviews scheduled.');
        }

        if ($application->hasInterview()) {
            return back()->with('error', 'Interview already scheduled for this application.');
        }

        // Validation
        $validated = $request->validate([
            'scheduled_at' => 'required|date|after:now',
            'interview_type' => 'required|in:onsite,online',
            'location' => 'required_if:interview_type,onsite|nullable|string|max:255',
            'meeting_link' => 'required_if:interview_type,online|nullable|url|max:500',
            'notes' => 'nullable|string|max:1000',
        ], [
            'scheduled_at.after' => 'Interview must be scheduled for a future date and time.',
            'location.required_if' => 'Location is required for onsite interviews.',
            'meeting_link.required_if' => 'Meeting link is required for online interviews.',
            'meeting_link.url' => 'Please provide a valid meeting link URL.',
        ]);

        // Create interview
        $interview = Interview::create([
            'application_id' => $application->id,
            'scheduled_at' => $validated['scheduled_at'],
            'interview_type' => $validated['interview_type'],
            'location' => $validated['location'] ?? null,
            'meeting_link' => $validated['meeting_link'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => 'scheduled',
        ]);

        // Update application status
        $application->update([
            'application_status' => 'interview_scheduled',
            'status_updated_at' => now(),
        ]);

        // Send notification to job seeker
        $jobSeekerUser = $application->jobSeeker->user;
        
        $interviewDetails = $validated['interview_type'] === 'online'
            ? "Meeting Link: {$validated['meeting_link']}"
            : "Location: {$validated['location']}";

        Notification::create([
            'user_id' => $jobSeekerUser->id,
            'type' => 'interview_scheduled',
            'title' => 'Interview Scheduled! 📅',
            'message' => "Your interview for {$application->job->job_title} has been scheduled on " . 
                         \Carbon\Carbon::parse($validated['scheduled_at'])->format('M d, Y \a\t h:i A') . 
                         ". {$interviewDetails}",
            'data' => [
                'job_id' => $application->job->id,
                'application_id' => $application->id,
                'interview_id' => $interview->id,
                'job_title' => $application->job->job_title,
                'scheduled_at' => $validated['scheduled_at'],
                'interview_type' => $validated['interview_type'],
            ],
            'action_url' => route('users.interviews.show', $interview->id),
            'icon' => 'bi-calendar-check-fill',
            'color' => 'info',
        ]);

        return back()->with('success', 'Interview scheduled successfully!');
    }

    /**
     * Update interview status
     */
    public function updateInterviewStatus(Request $request, Interview $interview)
    {
        $user = Auth::user();

        // Authorization check
        if ($interview->application->job->employer_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => 'required|in:completed,cancelled',
        ]);

        $interview->update([
            'status' => $validated['status'],
        ]);

        // Update application status if interview completed
        if ($validated['status'] === 'completed') {
            $interview->application->update([
                'application_status' => 'interviewed',
                'status_updated_at' => now(),
            ]);

            // Notify job seeker
            Notification::create([
                'user_id' => $interview->application->jobSeeker->user->id,
                'type' => 'interview_completed',
                'title' => 'Interview Completed ✅',
                'message' => "Your interview for {$interview->application->job->job_title} has been marked as completed. We'll get back to you soon!",
                'data' => [
                    'job_id' => $interview->application->job->id,
                    'application_id' => $interview->application->id,
                    'interview_id' => $interview->id,
                ],
                'action_url' => route('users.applications'),
                'icon' => 'bi-check-circle-fill',
                'color' => 'success',
            ]);

            $message = 'Interview marked as completed.';
        } else {
            // Notify job seeker of cancellation
            Notification::create([
                'user_id' => $interview->application->jobSeeker->user->id,
                'type' => 'interview_cancelled',
                'title' => 'Interview Cancelled ❌',
                'message' => "Your interview for {$interview->application->job->job_title} has been cancelled. The employer will reach out if they wish to reschedule.",
                'data' => [
                    'job_id' => $interview->application->job->id,
                    'application_id' => $interview->application->id,
                    'interview_id' => $interview->id,
                ],
                'action_url' => route('users.applications'),
                'icon' => 'bi-x-circle-fill',
                'color' => 'danger',
            ]);

            $message = 'Interview cancelled.';
        }

        return back()->with('success', $message);
    }

    /**
     * Cancel interview
     */
    public function cancelInterview(Interview $interview)
    {
        $user = Auth::user();

        // Authorization check
        if ($interview->application->job->employer_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($interview->status !== 'scheduled') {
            return back()->with('error', 'Only scheduled interviews can be cancelled.');
        }

        $interview->update(['status' => 'cancelled']);

        // Update application status back to shortlisted
        $interview->application->update([
            'application_status' => 'shortlisted',
            'status_updated_at' => now(),
        ]);

        // Notify job seeker
        Notification::create([
            'user_id' => $interview->application->jobSeeker->user->id,
            'type' => 'interview_cancelled',
            'title' => 'Interview Cancelled',
            'message' => "Your interview for {$interview->application->job->job_title} scheduled on " .
                         $interview->scheduled_at->format('M d, Y \a\t h:i A') . " has been cancelled.",
            'data' => [
                'job_id' => $interview->application->job->id,
                'application_id' => $interview->application->id,
                'interview_id' => $interview->id,
            ],
            'action_url' => route('users.applications'),
            'icon' => 'bi-x-circle-fill',
            'color' => 'warning',
        ]);

        return back()->with('success', 'Interview cancelled successfully.');
    }
}