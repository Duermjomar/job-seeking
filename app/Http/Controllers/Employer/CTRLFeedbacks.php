<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Feedbacks;

class CTRLFeedbacks extends Controller
{
    /**
     * Show the feedback submission form.
     */
    public function create()
    {
        return view('Employer.feedback.create');
    }

    /**
     * Store a new feedback submission.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'rate'  => 'required|in:1,3,5',
            'comm'  => 'required|string|min:10|max:2000',
        ]);

        Feedbacks::create([
            'user_id'  => Auth::id(),
            'email'    => $request->email,
            'rate'     => $request->rate,
            'comments' => $request->comm,
        ]);

        return redirect()->route('employer.feedback.create')
            ->with('status', 'Thank you! Your feedback has been submitted successfully.');
    }

    /**
     * Show the authenticated employer's feedback history.
     */
    public function index()
    {
        $myfeedbacks = Feedbacks::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('Employer.feedback.myfeedbacks', compact('myfeedbacks'));
    }
}