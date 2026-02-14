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
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'gender' => 'required|in:male,female,other',
            'birthdate' => 'required|date|before:today',
            'address' => 'required|string|max:255',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120', // 5MB max
            'profile_summary' => 'required|string|min:50'
        ]);

        $user = auth()->user();
        $jobSeeker = $user->jobSeeker;

        // Update user name
        $user->update(['name' => $validated['name']]);

        // Handle resume upload SEPARATELY - don't include in $validated
        if ($request->hasFile('resume')) {
            // Delete old resume if exists
            if ($jobSeeker->resume && Storage::disk('public')->exists($jobSeeker->resume)) {
                Storage::disk('public')->delete($jobSeeker->resume);
            }
            
            // Get the uploaded file
            $resumeFile = $request->file('resume');
            
            // Get original client filename
            $originalFilename = $resumeFile->getClientOriginalName();
            
            // Store with exact original name in resumes folder
            $storedPath = $resumeFile->storeAs('resumes', $originalFilename, 'public');
            
            // Update job seeker resume field directly
            $jobSeeker->resume = $storedPath;
        }

        // Remove fields that shouldn't be mass assigned
        unset($validated['name']);
        unset($validated['resume']); // Remove resume from validated to avoid overwriting

        // Update the job seeker profile with other fields
        $jobSeeker->fill($validated);
        $jobSeeker->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Delete resume
     */
    public function deleteResume()
    {
        try {
            $jobSeeker = auth()->user()->jobSeeker;

            if (!$jobSeeker || !$jobSeeker->resume) {
                return response()->json([
                    'success' => false,
                    'message' => 'No resume found to delete.'
                ], 404);
            }

            // Delete file from storage if it exists
            if (Storage::disk('public')->exists($jobSeeker->resume)) {
                Storage::disk('public')->delete($jobSeeker->resume);
            }

            // Update database to set resume to null
            $jobSeeker->resume = null;
            $jobSeeker->save();

            return response()->json([
                'success' => true,
                'message' => 'Resume deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting resume: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download resume with original filename
     */
    public function downloadResume()
    {
        $jobSeeker = auth()->user()->jobSeeker;

        if (!$jobSeeker || !$jobSeeker->resume) {
            abort(404, 'Resume not found');
        }

        $filePath = storage_path('app/public/' . $jobSeeker->resume);

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        // Get the filename from the stored path (basename extracts filename from path)
        $filename = basename($jobSeeker->resume);

        // Set correct MIME types
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];

        $contentType = $mimeTypes[strtolower($extension)] ?? 'application/octet-stream';

        // Return file for download with original filename
        return response()->download($filePath, $filename, [
            'Content-Type' => $contentType,
        ]);
    }
}