<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => ['required', 'string', 'max:15', 'unique:users', 'regex:/^(\+91[\s-]?)?[6-9]\d{9}$/'],
            'password' => 'required|string|min:8|confirmed',
        ], [
            'phone.required' => 'Phone number is required for order updates and delivery.',
            'phone.regex' => 'Please enter a valid Indian mobile number (e.g., 9876543210 or +91 9876543210).',
            'phone.unique' => 'This phone number is already registered. Try logging in instead.',
        ]);

        // Normalize phone: store as 10-digit number
        $phone = preg_replace('/[^0-9]/', '', $request->phone);
        if (strlen($phone) > 10) {
            $phone = substr($phone, -10);
        }

        // Generate email verification OTP
        $otp = rand(100000, 999999);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $phone,
            'password' => Hash::make($request->password),
            'email_otp' => $otp,
            'email_otp_expires_at' => now()->addMinutes(10),
        ]);

        // Send OTP email
        try {
            Mail::send('emails.verify-otp', ['otp' => $otp, 'name' => $user->name], function ($message) use ($user) {
                $message->to($user->email, $user->name)
                        ->subject('Verify Your Email - Shivara');
            });
        } catch (\Exception $e) {
            // If email fails, still register but skip verification
            $user->update(['email_verified_at' => now(), 'email_otp' => null, 'email_otp_expires_at' => null]);
            Auth::login($user);
            $this->linkGuestOrders($user);
            return redirect('/')->with('success', 'Welcome to Shivara! Your account has been created.');
        }

        Auth::login($user);
        $this->linkGuestOrders($user);

        return redirect()->route('verify.email.form')->with('success', 'Account created! Please verify your email with the OTP sent to ' . $user->email);
    }

    public function showVerifyEmail()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        if (auth()->user()->email_verified_at) {
            return redirect('/')->with('success', 'Email already verified!');
        }
        return view('auth.verify-email');
    }

    public function verifyEmail(Request $request)
    {
        $request->validate(['otp' => 'required|string|size:6']);
        $user = auth()->user();
        if (!$user) return redirect()->route('login');
        if ($user->email_verified_at) return redirect('/')->with('success', 'Email already verified!');

        if ($user->email_otp !== $request->otp) {
            return back()->withErrors(['otp' => 'Invalid OTP. Please check and try again.']);
        }
        if ($user->email_otp_expires_at && now()->isAfter($user->email_otp_expires_at)) {
            return back()->withErrors(['otp' => 'OTP has expired. Please request a new one.']);
        }

        $user->update(['email_verified_at' => now(), 'email_otp' => null, 'email_otp_expires_at' => null]);
        return redirect('/')->with('success', 'Email verified successfully! Welcome to Shivara!');
    }

    public function resendOtp()
    {
        $user = auth()->user();
        if (!$user) return redirect()->route('login');
        if ($user->email_verified_at) return redirect('/')->with('success', 'Email already verified!');

        $otp = rand(100000, 999999);
        $user->update(['email_otp' => $otp, 'email_otp_expires_at' => now()->addMinutes(10)]);

        try {
            Mail::send('emails.verify-otp', ['otp' => $otp, 'name' => $user->name], function ($message) use ($user) {
                $message->to($user->email, $user->name)->subject('Verify Your Email - Shivara');
            });
            return back()->with('success', 'New OTP sent to ' . $user->email);
        } catch (\Exception $e) {
            return back()->withErrors(['otp' => 'Failed to send OTP. Please try again later.']);
        }
    }

    private function linkGuestOrders(User $user): void
    {
        $phone = $user->phone; // Stored as 10 digits
        $email = $user->email;

        if (!$phone && !$email) return;

        // Build flexible phone patterns for matching
        $phonePatterns = [];
        if ($phone && strlen($phone) >= 10) {
            $last10 = substr($phone, -10);
            $phonePatterns = [
                $last10,                    // 9876543210
                '+91' . $last10,            // +919876543210
                '+91 ' . $last10,           // +91 9876543210
                '91' . $last10,             // 919876543210
                '0' . $last10,              // 09876543210
            ];
        }

        $guestOrders = Order::whereNull('user_id')
            ->whereHas('address', function ($query) use ($phonePatterns, $email) {
                $query->where(function ($q) use ($phonePatterns, $email) {
                    // Match by phone (try all formats)
                    foreach ($phonePatterns as $pattern) {
                        $q->orWhere('phone', $pattern);
                    }
                    // Also try LIKE match for last 10 digits
                    if (count($phonePatterns) > 0) {
                        $q->orWhere('phone', 'LIKE', '%' . $phonePatterns[0]);
                    }
                    // Match by email
                    if ($email) {
                        $q->orWhere('email', $email);
                    }
                });
            })
            ->get();

        if ($guestOrders->isEmpty()) {
            \Log::info('linkGuestOrders: No guest orders found', [
                'user_id' => $user->id,
                'phone' => $phone,
                'email' => $email,
            ]);
            return;
        }

        \Log::info('linkGuestOrders: Linking ' . $guestOrders->count() . ' orders to user ' . $user->id);

        foreach ($guestOrders as $order) {
            $order->update(['user_id' => $user->id]);
            if ($order->address && !$order->address->user_id) {
                $order->address->update(['user_id' => $user->id]);
            }
        }
    }
}
