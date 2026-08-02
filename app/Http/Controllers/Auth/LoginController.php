<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Rate limiting: 5 attempts per minute per email+IP
        $throttleKey = Str::lower($request->email) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            if (!auth()->user()->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                return back()->withErrors([
                    'email' => 'Your account has been deactivated. Please contact support.',
                ])->onlyInput('email');
            }

            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            // Link any guest orders to this user
            $this->linkGuestOrders(auth()->user());

            if (auth()->user()->isAdmin()) {
                return redirect()->intended('/admin/dashboard');
            }
            return redirect()->intended('/');
        }

        RateLimiter::hit($throttleKey, 60);

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

    /**
     * Link guest orders to the logged-in user based on phone/email match
     */
    private function linkGuestOrders($user): void
    {
        $phone = $user->phone;
        $email = $user->email;

        if (!$phone && !$email) return;

        $phonePatterns = [];
        if ($phone && strlen($phone) >= 10) {
            $last10 = substr($phone, -10);
            $phonePatterns = [
                $last10,
                '+91' . $last10,
                '+91 ' . $last10,
                '91' . $last10,
                '0' . $last10,
            ];
        }

        $guestOrders = Order::whereNull('user_id')
            ->whereHas('address', function ($query) use ($phonePatterns, $email) {
                $query->where(function ($q) use ($phonePatterns, $email) {
                    foreach ($phonePatterns as $pattern) {
                        $q->orWhere('phone', $pattern);
                    }
                    if (count($phonePatterns) > 0) {
                        $q->orWhere('phone', 'LIKE', '%' . $phonePatterns[0]);
                    }
                    if ($email) {
                        $q->orWhere('email', $email);
                    }
                });
            })
            ->get();

        foreach ($guestOrders as $order) {
            $order->update(['user_id' => $user->id]);
            if ($order->address && !$order->address->user_id) {
                $order->address->update(['user_id' => $user->id]);
            }
        }

        if ($guestOrders->count() > 0) {
            \Log::info("Linked {$guestOrders->count()} guest orders to user {$user->id} on login");
        }
    }
}
