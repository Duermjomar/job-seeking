<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class JobSeekerController extends Controller
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
    public function store(Request $request)
    {
        //
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

    public function updateProfile(Request $request)
    {

        $validated = $request->validate([
            'phone' => 'required|string|max:20',
            'gender' => 'required|in:male,female,other',
            'birthdate' => 'required|date|before:today',
            'address' => 'required|string|max:255',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'profile_summary' => 'required|string|min:50'
        ]);

        $jobSeeker = auth()->user()->jobSeeker;

        if ($request->hasFile('resume')) {
            // Delete old resume
            if ($jobSeeker->resume) {
                Storage::delete($jobSeeker->resume);
            }
            // Store new resume
            $validated['resume'] = $request->file('resume')->store('resumes', 'public');
        }

        $jobSeeker->update($validated);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}
