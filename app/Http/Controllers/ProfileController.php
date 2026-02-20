<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password; 
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

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

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




}
