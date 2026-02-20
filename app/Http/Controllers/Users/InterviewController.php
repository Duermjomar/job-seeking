<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Interview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterviewController extends Controller
{
    /**
     * Display the interview details for a job seeker
     */
    public function show(Interview $interview)
    {
        $user = Auth::user();

        // Authorization: Only the job seeker who owns this application can view the interview
        if ($interview->application->job_seeker_id !== $user->jobSeeker->id) {
            abort(403, 'Unauthorized access to interview details.');
        }

        // Load necessary relationships
        $interview->load([
            'application.job.employer',
            'application.jobSeeker.user'
        ]);

        return view('Users.interviews.show', compact('interview'));
    }

    /**
     * Display all interviews for the authenticated job seeker
     */
    public function index()
    {
        $user = Auth::user();
        $jobSeeker = $user->jobSeeker;

        if (!$jobSeeker) {
            return redirect()->route('users.applications')
                ->with('error', 'Job seeker profile not found.');
        }

        // Get all interviews for this job seeker
        $interviews = Interview::whereHas('application', function ($query) use ($jobSeeker) {
            $query->where('job_seeker_id', $jobSeeker->id);
        })
            ->with(['application.job.employer'])
            ->orderBy('scheduled_at', 'desc')
            ->get();

        // Separate by status
        $upcomingInterviews = $interviews->where('status', 'scheduled')
            ->filter(function ($interview) {
                return \Carbon\Carbon::parse($interview->scheduled_at)->isFuture();
            });

        $pastInterviews = $interviews->where('status', 'completed')
            ->merge($interviews->where('status', 'cancelled'));

        $scheduledCount = $upcomingInterviews->count();
        $completedCount = $interviews->where('status', 'completed')->count();
        $cancelledCount = $interviews->where('status', 'cancelled')->count();

        return view('Users.interviews.index', compact(
            'interviews',
            'upcomingInterviews',
            'pastInterviews',
            'scheduledCount',
            'completedCount',
            'cancelledCount'
        ));
    }
}