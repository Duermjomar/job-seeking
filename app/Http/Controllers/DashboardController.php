<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Application;
use App\Models\Job;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->roles->first()->name ?? null;

        // ================= ADMIN =================
        if ($role === 'admin') {

            // Main Statistics
            $totalUsers = \App\Models\User::count();
            $totalJobs = \App\Models\Job::count();
            $totalApplications = \App\Models\Application::count();

            // Count employers (users with 'employer' role)
            $totalEmployers = \App\Models\User::whereHas('roles', function ($query) {
                $query->where('name', 'employer');
            })->count();

            // Application Statistics
            $pendingApplications = \App\Models\Application::where('application_status', 'pending')->count();
            $acceptedApplications = \App\Models\Application::where('application_status', 'accepted')->count();

            // Active Job Seekers (those who have submitted at least one application)
            $activeJobSeekers = \App\Models\JobSeeker::has('applications')->count();

            // Applications submitted today
            $todayApplications = \App\Models\Application::whereDate('created_at', today())->count();

            // Recent Activity - Last 5 registered users with their roles
            $recentUsers = \App\Models\User::with('roles')
                ->latest()
                ->take(5)
                ->get();

            // Recent Jobs - Last 5 jobs posted with application counts
            $recentJobs = \App\Models\Job::withCount('applications')
                ->latest()
                ->take(5)
                ->get();

            return view('admin.dashboard', compact(
                'totalUsers',
                'totalJobs',
                'totalApplications',
                'totalEmployers',
                'pendingApplications',
                'acceptedApplications',
                'activeJobSeekers',
                'todayApplications',
                'recentUsers',
                'recentJobs'
            ));
        }

        // ================= EMPLOYER =================
        if ($role === 'employer') {

            $totalPostedJobs = Job::where('employer_id', $user->id)->count();

            $totalApplicants = Application::whereHas('job', function ($q) use ($user) {
                $q->where('employer_id', $user->id);
            })->count();
            return view('employer.dashboard', compact(
                'totalPostedJobs',
                'totalApplicants'
            ));
        }

        // ================= EMPLOYEE / JOB SEEKER =================
        if ($role === 'user') { // <-- use 'user' role

            // Get the job seeker profile; create automatically if missing
            $jobSeeker = $user->jobSeeker ?? \App\Models\JobSeeker::create([
                'user_id' => $user->id,
                'profile_summary' => null,
                'resume' => null,
                'phone' => null,
                'address' => null,
                'birthdate' => null,
                'gender' => null,
            ]);

            // Search query (optional)
            $search = request('search');

            // Total application stats
            $totalApplications = Application::where('job_seeker_id', $jobSeeker->id)->count();
            $pendingApplications = Application::where('job_seeker_id', $jobSeeker->id)
                ->where('application_status', 'pending')->count();
            $acceptedApplications = Application::where('job_seeker_id', $jobSeeker->id)
                ->where('application_status', 'accepted')->count();
            $rejectedApplications = Application::where('job_seeker_id', $jobSeeker->id)
                ->where('application_status', 'rejected')->count();

            // Latest jobs (open, random order, paginated)
            $latestJobs = Job::when($search, function ($query, $search) {
                $query->where('job_title', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('job_type', 'like', "%{$search}%");
            })
                ->where('status', 'open')
                ->inRandomOrder()   // randomize for equal exposure
                ->paginate(8);       // adjust per-page count as needed

            return view('users.dashboard', compact(
                'jobSeeker',
                'totalApplications',
                'pendingApplications',
                'acceptedApplications',
                'rejectedApplications',
                'latestJobs',
                'search'
            ));
        }


        // If role is not recognized
        abort(403, 'Unauthorized access.');
    }
}
