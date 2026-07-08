<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Login or register with phone number (WhatsApp)
     */
    public function loginWithPhone(Request $request)
    {
        $request->validate([
            'phone' => 'required|digits:10',
            'name' => 'nullable|string|max:255',
        ]);

        $phone = $request->phone;

        // Find existing user by phone
        $user = User::where('phone', $phone)->first();

        if (!$user) {
            // New user — require name for registration
            if (!$request->filled('name')) {
                return back()->withInput()->withErrors([
                    'phone' => 'This number is not registered. Please enter your name to create an account.'
                ]);
            }

            // Auto-register
            $user = User::create([
                'phone' => $phone,
                'name' => $request->name,
                'role' => 'customer',
                'is_active' => true,
            ]);
        }

        if (!$user->is_active) {
            return back()->withInput()->withErrors([
                'login' => 'Your account has been disabled. Please contact support.'
            ]);
        }

        // Update name if provided and user has no name
        if ($request->filled('name') && !$user->name) {
            $user->update(['name' => $request->name]);
        }

        // Login the user
        Auth::login($user, true);
        $request->session()->regenerate();

        if ($user->isAdmin()) {
            return redirect()->intended('/admin/dashboard');
        }

        return redirect()->intended('/')->with('success', 'Welcome back, ' . ($user->name ?: 'there') . '!');
    }

    /**
     * Legacy email login (kept for admin panel access)
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (auth()->user()->isAdmin()) {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
