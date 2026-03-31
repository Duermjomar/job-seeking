<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;

class AdminController extends Controller
{


    public function allJobs(Request $request)
    {
        $query = Job::with(['employer', 'applications'])
            ->withCount('applications');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by job type
        if ($request->filled('type')) {
            $query->where('job_type', $request->type);
        }

        // Search by title or company
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('job_title', 'like', '%' . $request->search . '%')
                    ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        $jobs = $query->latest()->paginate(15);

        $totalJobs = Job::count();
        $openJobs = Job::where('status', 'open')->count();
        $closedJobs = Job::where('status', 'closed')->count();

        return view('admin.jobs.index', compact('jobs', 'totalJobs', 'openJobs', 'closedJobs'));
    }
    //

    public function toggleStatus(Job $job)
    {
        $job->update([
            'status' => $job->status === 'open' ? 'closed' : 'open'
        ]);

        return back()->with('success', 'Job status updated successfully.');
    }

    public function destroyJob(Job $job)
    {
        $job->applications()->delete();
        $job->delete();

        return back()->with('success', 'Job deleted successfully.');
    }
}
