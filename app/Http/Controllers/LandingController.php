<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;

class LandingController extends Controller
{
    //
        public function index()
    {
        $latestJobs = Job::where('status', 'open')
            ->latest()
            ->take(6)
            ->get();

        return view('welcome', compact('latestJobs'));
    }
}
