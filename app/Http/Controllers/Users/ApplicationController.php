<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Application;
use App\Models\Job;
use App\Models\Notification;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class ApplicationController extends Controller
{
    public function store(Request $request, Job $job)
    {
        $user = Auth::user();
        $jobSeeker = $user->jobSeeker;

        if (!$jobSeeker) {
            return back()->with('error', 'Job seeker profile not found.');
        }

        if (!$jobSeeker->resume) {
            return back()->with('error', 'Please upload your resume before applying.');
        }

        $existingApplication = Application::where('job_id', $job->id)
            ->where('job_seeker_id', $jobSeeker->id)
            ->latest()
            ->first();

        if ($existingApplication) {

            if ($existingApplication->application_status === Application::STATUS_REJECTED) {

                // SAFELY handle null status_updated_at
                $rejectionDate = $existingApplication->status_updated_at ?? $existingApplication->updated_at;

                $daysSinceRejection = Carbon::parse($rejectionDate)->diffInDays(now());
                $cooldownDays = 30;

                if ($daysSinceRejection < $cooldownDays) {
                    $remainingDays = $cooldownDays - $daysSinceRejection;
                    return back()->with(
                        'error',
                        "You can re-apply after {$remainingDays} more days."
                    );
                }

            } else {
                return back()->with('error', 'You already have an active application for this job.');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validationRules = [];

        $hasTemplates = $job->templates && $job->templates->count() > 0;

        if ($hasTemplates) {
            foreach ($job->templates as $template) {
                $validationRules["template_files.{$template->id}"] =
                    'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048';
            }

            // Application letter is not used when templates exist
        } else {
            $validationRules['application_letter'] =
                'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048';
        }

        $request->validate($validationRules);

        /*
        |--------------------------------------------------------------------------
        | Reapply Logic
        |--------------------------------------------------------------------------
        */

        $reapplyCount = 0;

        if (
            $existingApplication &&
            $existingApplication->application_status === Application::STATUS_REJECTED
        ) {

            $reapplyCount = $existingApplication->reapply_count + 1;

            // Delete old files safely
            foreach ($existingApplication->files as $file) {
                if (Storage::disk('public')->exists($file->file_path)) {
                    Storage::disk('public')->delete($file->file_path);
                }
                $file->delete();
            }

            $existingApplication->delete();
        }

        /*
        |--------------------------------------------------------------------------
        | Create Application
        |--------------------------------------------------------------------------
        */

        $application = Application::create([
            'job_id' => $job->id,
            'job_seeker_id' => $jobSeeker->id,
            'application_status' => Application::STATUS_PENDING,
            'applied_at' => now(),
            'reapply_count' => $reapplyCount,
            'status_updated_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Upload Template Files
        |--------------------------------------------------------------------------
        */

        if ($hasTemplates && $request->hasFile('template_files')) {

            foreach ($request->file('template_files') as $templateId => $file) {

                if ($file) {

                    // Use original filename (prefixed with application ID to avoid collisions)
                    $originalName = $application->id . '_' . $file->getClientOriginalName();

                    $filePath = $file->storeAs(
                        'applications/templates',
                        $originalName,
                        'public'
                    );

                    $application->files()->create([
                        'file_path' => $filePath,
                        'file_type' => 'other',
                        'original_name' => $file->getClientOriginalName(), // store clean original name
                        'mime_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Upload Application Letter (only when no templates)
        |--------------------------------------------------------------------------
        */

        if (!$hasTemplates && $request->hasFile('application_letter')) {
            $file = $request->file('application_letter');
            $originalName = $application->id . '_application_letter_' . $file->getClientOriginalName();

            $filePath = $file->storeAs(
                'applications/letters',
                $originalName,
                'public'
            );

            $application->files()->create([
                'file_path' => $filePath,
                'file_type' => 'application_letter',
                'original_name' => $file->getClientOriginalName(), // store clean original name
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Notifications
        |--------------------------------------------------------------------------
        */

        // Job Seeker Notification
        Notification::create([
            'user_id' => $user->id,
            'type' => $reapplyCount > 0 ? 'reapplication_submitted' : 'application_submitted',
            'title' => $reapplyCount > 0
                ? 'Re-Application Submitted!'
                : 'Application Submitted!',
            'message' => "Your application for {$job->job_title} has been submitted.",
            'data' => [
                'job_id' => $job->id,
                'application_id' => $application->id,
                'reapply_count' => $reapplyCount,
            ],
            'action_url' => route('users.applications', [
                'highlight' => $job->id,
            ]),
            'icon' => 'bi-send-fill',
            'color' => 'success',
        ]);

        // Employer Notification
        Notification::create([
            'user_id' => $job->employer_id,
            'type' => $reapplyCount > 0 ? 'new_reapplication' : 'new_application',
            'title' => $reapplyCount > 0
                ? 'New Re-Application Received!'
                : 'New Application Received!',
            'message' => "{$user->name} applied for {$job->job_title}",
            'data' => [
                'job_id' => $job->id,
                'application_id' => $application->id,
            ],
            'action_url' => route('employer.jobs.applicants', [
                'job' => $job->id,
                'highlight' => $application->id,
            ]),
            'icon' => $reapplyCount > 0 ? 'bi-arrow-repeat' : 'bi-person-plus-fill',
            'color' => $reapplyCount > 0 ? 'warning' : 'primary',
        ]);

        return back()->with(
            'success',
            $reapplyCount > 0
            ? "Re-application submitted successfully! Attempt #" . ($reapplyCount + 1)
            : 'Application submitted successfully!'
        );
    }


    public function trackApplications()
    {
        $user = Auth::user();
        $jobSeeker = $user->jobSeeker;

        $totalApplications = 0;
        $pendingApplications = 0;
        $interviewApplications = 0;
        $acceptedApplications = 0;
        $rejectedApplications = 0;
        $applications = collect();

        if ($jobSeeker) {
            // Load applications with job, files, and interview relationships
            $applications = $jobSeeker->applications()
                ->with(['job', 'files', 'interview'])
                ->latest('applied_at')
                ->get();

            $totalApplications = $applications->count();
            $pendingApplications = $applications->where('application_status', 'pending')->count();

            // Count all interview-related statuses
            $interviewApplications = $applications->whereIn('application_status', [
                'shortlisted',
                'interview_scheduled',
                'interviewed'
            ])->count();

            $acceptedApplications = $applications->where('application_status', 'accepted')->count();
            $rejectedApplications = $applications->where('application_status', 'rejected')->count();
        }

        return view('Users.trackApplications', compact(
            'applications',
            'totalApplications',
            'pendingApplications',
            'interviewApplications',
            'acceptedApplications',
            'rejectedApplications'
        ));
    }

    /**
     * Check if user can re-apply to a job
     */
    public function checkReapply(Job $job)
    {
        $user = Auth::user();
        $jobSeeker = $user->jobSeeker;

        if (!$jobSeeker) {
            return response()->json([
                'can_reapply' => false,
                'message' => 'Job seeker profile not found.'
            ]);
        }

        $existingApplication = Application::where('job_id', $job->id)
            ->where('job_seeker_id', $jobSeeker->id)
            ->latest()
            ->first();

        if (!$existingApplication) {
            return response()->json([
                'can_reapply' => true,
                'message' => 'No previous application found.'
            ]);
        }

        // Check if application is still active (not rejected)
        if ($existingApplication->application_status !== 'rejected') {
            return response()->json([
                'can_reapply' => false,
                'message' => 'You already have an active application for this job.',
                'current_status' => $existingApplication->application_status
            ]);
        }

        // Check cooldown period for rejected applications
        $rejectionDate = $existingApplication->status_updated_at ?? $existingApplication->updated_at;
        $daysSinceRejection = Carbon::parse($rejectionDate)->diffInDays(now());
        $cooldownDays = 30;

        if ($daysSinceRejection < $cooldownDays) {
            $remainingDays = $cooldownDays - $daysSinceRejection;
            return response()->json([
                'can_reapply' => false,
                'message' => "You can re-apply after {$remainingDays} more days.",
                'days_remaining' => $remainingDays,
                'cooldown_days' => $cooldownDays
            ]);
        }

        return response()->json([
            'can_reapply' => true,
            'message' => 'You can re-apply to this job.',
            'reapply_count' => $existingApplication->reapply_count
        ]);
    }
}