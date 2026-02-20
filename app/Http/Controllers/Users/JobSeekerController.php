<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class JobSeekerController extends Controller
{
    public function index() {}
    public function create() {}
    public function store(Request $request) {}
    public function show(string $id) {}
     public function userEditProfile()
    {
        $user = Auth::user();
        $jobSeeker = $user->jobSeeker;

        return view('Users.profile.edit', compact('jobSeeker'));
    }
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'phone'           => 'required|string|max:20',
            'gender'          => 'required|in:male,female,other',
            'birthdate'       => 'required|date|before:today',
            'address'         => 'required|string|max:255',
            'resume'          => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'profile_summary' => 'required|string|min:50',
        ]);

        $user      = auth()->user();
        $jobSeeker = $user->jobSeeker;

        // Update user name
        $user->update(['name' => $validated['name']]);

        // Handle resume upload — do this BEFORE fill() so we control both columns manually
        if ($request->hasFile('resume')) {
            $resumeFile   = $request->file('resume');

            // Capture the original filename BEFORE doing anything with the file
            $originalName = $resumeFile->getClientOriginalName();         // e.g. "John_Doe_CV.pdf"
            $extension    = $resumeFile->getClientOriginalExtension();    // e.g. "pdf"
            $nameWithout  = pathinfo($originalName, PATHINFO_FILENAME);   // e.g. "John_Doe_CV"

            // Build a unique storage name to prevent collisions between users
            $uniqueFilename = $nameWithout . '_' . $user->id . '_' . time() . '.' . $extension;

            // Delete the old resume file from storage if one exists
            if ($jobSeeker->resume && Storage::disk('public')->exists($jobSeeker->resume)) {
                Storage::disk('public')->delete($jobSeeker->resume);
            }

            // Store using storeAs so WE control the filename, not Laravel
            $storedPath = $resumeFile->storeAs('resumes', $uniqueFilename, 'public');

            // Save both columns directly — do NOT go through fill() for these
            $jobSeeker->resume          = $storedPath;    // storage path  e.g. resumes/John_Doe_CV_8_1714500000.pdf
            $jobSeeker->resume_original = $originalName;  // display name  e.g. John_Doe_CV.pdf
        }

        // Remove fields that must not be mass-assigned to jobSeeker
        unset($validated['name']);
        unset($validated['resume']); // IMPORTANT: remove so fill() doesn't overwrite resume column

        // Update all other profile fields
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
                    'message' => 'No resume found to delete.',
                ], 404);
            }

            // Delete physical file from storage
            if (Storage::disk('public')->exists($jobSeeker->resume)) {
                Storage::disk('public')->delete($jobSeeker->resume);
            }

            // Clear both columns directly — not through fill()
            $jobSeeker->resume          = null;
            $jobSeeker->resume_original = null;
            $jobSeeker->save();

            return response()->json([
                'success' => true,
                'message' => 'Resume deleted successfully!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting resume: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Download resume — serves the file under the original filename the user uploaded.
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

        // Use the stored original name so the user gets back their own filename;
        // fall back to the basename of the stored path for old records
        $downloadName = $jobSeeker->resume_original ?? basename($jobSeeker->resume);

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $mimeTypes = [
            'pdf'  => 'application/pdf',
            'doc'  => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];

        $contentType = $mimeTypes[strtolower($extension)] ?? 'application/octet-stream';

        return response()->download($filePath, $downloadName, [
            'Content-Type' => $contentType,
        ]);
    }
}