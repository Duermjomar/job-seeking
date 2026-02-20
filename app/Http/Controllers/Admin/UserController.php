<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Feedbacks;  // Fixed namespace
use Illuminate\Http\Request;
use Gate;
use DB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Gate::denies('admin-access')) {
            return redirect('errors.403');
        }

        $allusers = User::where('id', '>=', '1')->paginate(10);

        return view('admin.users.index')->with('allusers', $allusers);
    }

    public function create() {}
    public function store(Request $request) {}
    public function show(User $user) {}
    public function edit(User $user) {}
    public function update(Request $request, User $user) {}
    public function destroy(User $user) {}

    public function userFeedback()
    {
        $allfeedbacks = Feedbacks::latest()->paginate(10);  // Fixed :: and namespace

        return view('admin.users.feedbacks.show')->with('allfeedbacks', $allfeedbacks);
    }

    public function viewUser(User $user)
    {
        $user->load([
            'roles',
            'jobSeeker.applications.job',
            'jobSeeker.applications.files',
            'jobSeeker.applications.interview',
            'employer.jobs',
            'employer.jobs.applications',
            'employer.jobs.applications.interview',
        ]);

        return view('admin.users.view', compact('user'));
    }
}