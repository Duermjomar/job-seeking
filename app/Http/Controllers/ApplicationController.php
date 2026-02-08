<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;
use App\Models\Job;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Job $job)
    {
        $user = Auth::user();
        $jobSeeker = $user->jobSeeker;

        if (!$jobSeeker) {
            abort(403, 'Job seeker profile not found.');
        }

        // ✅ Prevent duplicate application
        $alreadyApplied = Application::where('job_id', $job->id)
            ->where('job_seeker_id', $jobSeeker->id)
            ->exists();

        if ($alreadyApplied) {
            return back()->with('error', 'You already applied for this job.');
        }

        // ✅ Validate files (PDF or Image)
        $request->validate([
            'resume' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'application_letter' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // ✅ Create application FIRST
        $application = Application::create([
            'job_id' => $job->id,
            'job_seeker_id' => $jobSeeker->id,
            'application_status' => 'pending',
            'applied_at' => now(),
        ]);

        // ✅ Handle Resume Upload
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')
                ->store('applications/resumes', 'public');

            $application->files()->create([
                'file_path' => $resumePath,
                'file_type' => 'resume',
            ]);
        }

        // ✅ Handle Application Letter Upload
        if ($request->hasFile('application_letter')) {
            $letterPath = $request->file('application_letter')
                ->store('applications/letters', 'public');

            $application->files()->create([
                'file_path' => $letterPath,
                'file_type' => 'application_letter',
            ]);
        }

        return back()->with('success', 'Application submitted successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function dashboard()
    {
        $user = Auth::user();

        // Ensure user is job seeker
        if ($user->role !== 'job_seeker') {
            abort(403);
        }

        $jobSeeker = $user->jobSeeker;

        $totalApplications = Application::where('job_seeker_id', $jobSeeker->id)->count();

        $pendingApplications = Application::where('job_seeker_id', $jobSeeker->id)
            ->where('application_status', 'pending')->count();

        $acceptedApplications = Application::where('job_seeker_id', $jobSeeker->id)
            ->where('application_status', 'accepted')->count();

        $rejectedApplications = Application::where('job_seeker_id', $jobSeeker->id)
            ->where('application_status', 'rejected')->count();

        $latestJobs = Job::where('status', 'open')
            ->latest()
            ->take(5)
            ->get();

        return view('jobseeker.dashboard', compact(
            'jobSeeker',
            'totalApplications',
            'pendingApplications',
            'acceptedApplications',
            'rejectedApplications',
            'latestJobs'
        ));



    }

    // Employer: Accept / Reject application
    public function updateStatus(Request $request, Application $application)
    {
        $user = Auth::user();

        if ($application->job->employer_id !== $user->id)
            abort(403);

        $request->validate([
            'status' => 'required|in:accepted,rejected',
        ]);

        $application->update([
            'application_status' => $request->status
        ]);

        return back()->with('success', 'Application status updated.');
    }
}
