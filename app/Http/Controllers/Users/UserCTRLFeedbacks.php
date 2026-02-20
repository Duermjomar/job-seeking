<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feedbacks;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserCTRLFeedbacks extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the feedback submission form.
     */
    public function create()
    {
        return view('users.feedback.create');
    }

    /**
     * Store a newly created feedback in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'rate'  => ['required', 'in:1,3,5'],
            'comm'  => ['required', 'string', 'max:1000'],
        ]);

        // Save the feedback
        $feedback = Feedbacks::create([
            'user_id'  => Auth::id(),
            'email'    => $request->email,
            'rate'     => $request->rate,
            'comments' => $request->comm,
        ]);

        // Map numeric rating to a readable label
        $ratingLabels = [
            '1' => '⭐ Poor (1/5)',
            '3' => '⭐⭐⭐ Average (3/5)',
            '5' => '⭐⭐⭐⭐⭐ Excellent (5/5)',
        ];
        $ratingLabel = $ratingLabels[$request->rate] ?? $request->rate . '/5';

        // Determine notification color based on rating
        $ratingColors = [
            '1' => 'danger',
            '3' => 'warning',
            '5' => 'success',
        ];
        $notifColor = $ratingColors[$request->rate] ?? 'primary';

        // Notify all admin users
        $admins = User::whereHas('roles', function ($q) {
            $q->where('name', 'admin');
        })->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id'    => $admin->id,
                'type'       => 'feedback',
                'title'      => 'New Feedback Received',
                'message'    => "A user ({$request->email}) submitted feedback rated {$ratingLabel}.",
                'data'       => json_encode([
                    'feedback_id' => $feedback->id,
                    'email'       => $request->email,
                    'rate'        => $request->rate,
                    'comment'     => Str::limit($request->comm, 100),
                ]),
                'read'       => false,
                'action_url' => route('admin.notifications.index'),
                'icon'       => 'bi-chat-square-text-fill',
                'color'      => $notifColor,
            ]);
        }

        return redirect()->back()->with('status', 'Feedback Submitted Successfully!');
    }

    /**
     * Display the current user's feedback history.
     */
    public function myfeedback()
    {
        $myfeedbacks = Feedbacks::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('users.feedback.myfeedbacks', compact('myfeedbacks'));
    }
}