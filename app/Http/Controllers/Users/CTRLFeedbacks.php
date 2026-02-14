<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Users\Feedbacks;
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
        
        return view('users.feedback.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'rate' => ['required', 'in:1,3,5'],
            'comm' => ['required', 'string', 'max:1000'],
        ]);

        Feedbacks::create([
            'email' => $request->email,
            'rate' => $request->rate,
            'comments' => $request->comm
        ]);

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
     * Display user's feedback list
     */
    public function myfeedback()
    {
        $myfeedbacks = Feedbacks::where('email', Auth::user()->email)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('users.feedback.myfeedbacks', compact('myfeedbacks'));
    }
}