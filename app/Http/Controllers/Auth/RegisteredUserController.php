<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\RandomStringGenerator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(RandomStringGenerator $generator, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email:rfc,dns', 'max:255', 'unique:users'],
            'username' => ['required', 'min:3', 'max:20', 'alpha_dash', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
            'terms' => ['accepted'],
        ], [
            // Email
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email address cannot be more that 255 characters.',
            'email.unique' => 'An account already exists for this email address.',

            // Username
            'username.required' => 'Please enter a username.',
            'username.min' => 'Username must be at least 3 characters.',
            'username.max' => 'Username cannot be more than 20 characters.',
            'username.alpha_dash' => 'Username can only contain letters, numbers, dashes, and underscores.',
            'username.unique' => 'That username is not available.',

            // Password
            'password.required' => 'Please enter a password for your account.',
            'password.string' => 'Please enter a valid password for your account.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.max' => 'Password cannot be more than 255 characters.',
            'password.confirmed' => 'Your password confirmation does not match.',

            // Terms
            'terms.accepted' => 'I agree to the <a href="'.route('pages.terms').'" target="_blank" rel="noopener noreferrer" class="link">Terms of Service</a> and <a href="'.route('pages.privacy').'" target="_blank" rel="noopener noreferrer" class="link">Privacy Policy</a>'
        ]);

        $user = User::create([
            'hex' => $generator->uniqueHexId(),
            'username' => strtolower($data['username']),
            'display_name' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'terms_accepted_at' => now(),
            'terms_version' => config('terms_version'),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('admin.dashboard', absolute: false));
    }
}
