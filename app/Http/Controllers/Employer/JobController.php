<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplicationTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JobController extends Controller
{
    /**
     * Show all applicants for a specific job
     */
    public function applicants(Job $job)
    {
        if ($job->employer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $applications = $job->applications()
            ->with(['jobSeeker.user', 'files'])
            ->latest()
            ->get();

        return view('employer.jobs.applicants', compact('job', 'applications'));
    }

    /**
     * Show the form for creating a new job
     */
    public function create()
    {
        $user = Auth::user();

        if (!$user->roles->contains('name', 'employer')) {
            abort(403, 'Unauthorized');
        }

        return view('employer.jobs.create');
    }

    /**
     * Store a newly created job
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
            'status' => 'required|in:open,closed',
            'application_templates.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:5120',
        ]);

        // Create the job
        $job = Job::create([
            'employer_id' => $user->id,
            'job_title' => $request->job_title,
            'job_description' => $request->job_description,
            'job_type' => $request->job_type,
            'salary' => $request->salary,
            'requirements' => $request->requirements,
            'location' => $request->location,
            'status' => $request->status,
        ]);

        // Handle multiple template uploads
        if ($request->hasFile('application_templates')) {
            foreach ($request->file('application_templates') as $file) {
                $filePath = $file->store('templates', 'public');

                JobApplicationTemplate::create([
                    'job_id' => $job->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_type' => $file->getClientOriginalExtension(),
                ]);
            }
        }

        return redirect()
            ->route('dashboard')
            ->with('success', 'Job posted successfully!');
    }

    /**
     * Display employer's posted jobs
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user->roles->contains('name', 'employer')) {
            abort(403, 'Unauthorized');
        }

        $jobs = Job::where('employer_id', $user->id)
            ->withCount(['applications', 'templates'])
            ->latest()
            ->paginate(10);

        return view('employer.jobs.index', compact('jobs'));
    }

    /**
     * Show a specific job
     */
    // public function show(Job $job)
    // {
    //     if ($job->employer_id !== auth()->id()) {
    //         abort(403, 'Unauthorized action.');
    //     }

    //     $job->load(['applications.jobSeeker.user', 'applications.files', 'templates']);

    //     return view('employer.jobs.show', compact('job'));
    // }

    /**
     * Show the form for editing a job
     */
    public function edit(Job $job)
    {
        $user = Auth::user();

        if ($job->employer_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $job->load('templates');

        return view('employer.jobs.edit', compact('job'));
    }

    /**
     * Update a job
     */
    public function update(Request $request, Job $job)
    {
        $user = Auth::user();

        if ($job->employer_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'job_title' => 'required|string|max:255',
            'job_description' => 'required|string',
            'job_type' => 'required|in:full-time,part-time,internship',
            'salary' => 'nullable|integer',
            'requirements' => 'required|string',
            'location' => 'required|string|max:255',
            'status' => 'required|in:open,closed',
            'application_templates.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:5120',
        ]);

        $job->update([
            'job_title' => $request->job_title,
            'job_description' => $request->job_description,
            'job_type' => $request->job_type,
            'salary' => $request->salary,
            'requirements' => $request->requirements,
            'location' => $request->location,
            'status' => $request->status,
        ]);

        // Handle new template uploads
        if ($request->hasFile('application_templates')) {
            foreach ($request->file('application_templates') as $file) {
                $filePath = $file->store('templates', 'public');

                JobApplicationTemplate::create([
                    'job_id' => $job->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_type' => $file->getClientOriginalExtension(),
                ]);
            }
        }

        return redirect()
            ->route('dashboard')
            ->with('success', 'Job updated successfully!');
    }

    /**
     * Delete a template
     */
    public function deleteTemplate(JobApplicationTemplate $template)
    {
        $job = $template->job;

        if ($job->employer_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Delete file from storage
        Storage::disk('public')->delete($template->file_path);

        // Delete record
        $template->delete();

        return back()->with('success', 'Template deleted successfully!');
    }

    /**
     * Delete a job
     */
    public function destroy(Job $job)
    {
        $user = Auth::user();

        if ($job->employer_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        // Delete all template files
        foreach ($job->templates as $template) {
            Storage::disk('public')->delete($template->file_path);
        }

        $job->delete();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Job deleted successfully!');
    }

    public function viewTemplate($id)
    {
        $template = JobApplicationTemplate::findOrFail($id);

        $filePath = storage_path('app/public/' . $template->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        // Set correct MIME types
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        $contentType = $mimeTypes[$template->file_type] ?? 'application/octet-stream';

        // THIS IS THE IMPORTANT PART - "inline" makes it VIEW, not download
        return response()->file($filePath, [
            'Content-Type' => $contentType,
            'Content-Disposition' => 'inline; filename="' . $template->file_name . '"'
        ]);
    }
}