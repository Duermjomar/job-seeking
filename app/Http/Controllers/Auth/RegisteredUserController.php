<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        // Store intended URL if provided in query parameter
        if ($request->has('intended')) {
            session()->put('url.intended', $request->get('intended'));
        }

        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:user,employer'], // Validate role selection
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign role based on selection
        $user->roles()->attach(\App\Models\Role::where('name', $request->role)->first());

        event(new Registered($user));

        Auth::login($user);

        // Check for intended URL first
        $intended = session()->pull('url.intended');
        
        if ($intended) {
            return redirect($intended);
        }

        // Default redirect based on user role
        if ($request->role === 'user') {
            return redirect()->route('dashboard');
        }

        if ($request->role === 'employer') {
            return redirect()->route('dashboard'); // Update with your employer dashboard route
        }

        return redirect(RouteServiceProvider::HOME);
    }
}