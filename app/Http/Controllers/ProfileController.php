<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{

    public function accountSettings()
    {
        $user = auth()->user();

        // Redirect based on role
        if ($user->hasRole('admin')) {
            return view('admin.profile.account-settings');
        } elseif ($user->hasRole('employer')) {
            return view('employer.profile.account-settings');
        } else {
            return view('users.profile.account-settings');
        }
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'current_password' => 'required',
        ]);

        // Verify current password
        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update email
        auth()->user()->update([
            'email' => $request->email,
            'email_verified_at' => null, // Reset verification
        ]);

        return back()->with('success', 'Email address updated successfully! Please verify your new email.');
    }


    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        // Verify current password
        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update password
        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    /**
     * Delete Account
     */
    public function deleteAccount(Request $request)
    {
        $user = auth()->user();

        // Log the user out
        Auth::logout();

        // Delete associated job seeker profile if exists (for 'user' role)
        if ($user->hasRole('user') && $user->jobSeeker) {
            // Delete resume file if exists
            if ($user->jobSeeker->resume) {
                Storage::disk('public')->delete($user->jobSeeker->resume);
            }
            $user->jobSeeker->delete();
        }

        // Delete associated employer profile if exists (for 'employer' role)
        if ($user->hasRole('employer') && $user->employer) {
            // Delete any employer-related files if needed
            // Example: if ($user->employer->logo) { Storage::disk('public')->delete($user->employer->logo); }
            $user->employer->delete();
        }

        // Delete the user
        $user->delete();

        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Your account has been deleted successfully.');
    }

    public function userUpdateProfile(Request $request)
    {
        $user = Auth::user();
        $jobSeeker = $user->jobSeeker;

        // Validate the request - ONLY fields that exist in your migration
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'profile_summary' => 'nullable|string|max:1000',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120', // 5MB max
        ]);

        // Update user name
        $user->update([
            'name' => $validated['name']
        ]);

        // Prepare job seeker data - ONLY your existing fields
        $jobSeekerData = [
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'birthdate' => $validated['birthdate'],
            'gender' => $validated['gender'],
            'profile_summary' => $validated['profile_summary'],
        ];

        // Handle resume upload
        if ($request->hasFile('resume')) {
            // Delete old resume if exists
            if ($jobSeeker && $jobSeeker->resume) {
                Storage::disk('public')->delete($jobSeeker->resume);
            }

            // Store new resume
            $resumePath = $request->file('resume')->store('resumes', 'public');
            $jobSeekerData['resume'] = $resumePath;
        }

        // Update or create job seeker profile
        if ($jobSeeker) {
            $jobSeeker->update($jobSeekerData);
        } else {
            $user->jobSeeker()->create($jobSeekerData);
        }

        return redirect()
            ->route('users.profile.edit')
            ->with('success', 'Profile updated successfully!');
    }


    public function userEditProfile()
    {
        $user = Auth::user();
        $jobSeeker = $user->jobSeeker;

        return view('Users.profile.edit', compact('jobSeeker'));
    }
}
