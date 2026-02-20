<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Users\Feedbacks;
use App\Models\Notification;
use App\Models\User;
use Gate;
use Auth;

class CTRLFeedbacks extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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
        // denies the gate if 
        if(Gate::denies('user-access')){
            return redirect('errors.403');
        }
        
        return view('employer.feedback.create');
    }

    /**
     * Store a newly created resource in storage.
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
                    'comment'     => \Illuminate\Support\Str::limit($request->comm, 100),
                ]),
                'read'       => false,
                'action_url' => route('admin.userFeedback'),
                'icon'       => 'bi-chat-square-text-fill',
                'color'      => $notifColor,
            ]);
        }

        return redirect()->back()->with('status', 'Feedback Submitted Successfully!');
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

    /**
     * Display employer's feedback list
     */
    public function myfeedback()
    {
        $myfeedbacks = Feedbacks::where('email', Auth::user()->email)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('employer.feedback.myfeedbacks', compact('myfeedbacks'));
    }
}