<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Job;
use App\Models\JobCategory;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobs = Job::where('status', 'open')->latest()->paginate(10);
        return view('jobs.index', compact('jobs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      
    }

    /**
     * Store Job
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->roles->contains('name', 'employer')) {
            abort(403, 'Unauthorized');
        }

        $request->validate([

            'job_title' => 'required|string|max:255',
            'job_description' => 'required|string',
            'job_type' => 'required|in:full-time,part-time,internship',
            'salary' => 'nullable|integer',
            'requirements' => 'required|string',
            'location' => 'required|string|max:255',
        ]);

        Job::create([
            'employer_id' => $user->id, // USERS TABLE
            'category_id' => $request->category_id,
            'job_title' => $request->job_title,
            'job_description' => $request->job_description,
            'job_type' => $request->job_type,
            'salary' => $request->salary,
            'requirements' => $request->requirements,
            'location' => $request->location,
            'status' => 'open',
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Job posted successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Job $job)
    {
        return view('jobs.show', compact('job'));
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
}
