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
        $phone = $user->phone;
        $email = $user->email;

        $guestOrders = Order::whereNull('user_id')
            ->whereHas('address', function ($query) use ($phone, $email) {
                $query->where(function ($q) use ($phone, $email) {
                    if ($phone) {
                        $q->where('phone', 'LIKE', '%' . substr($phone, -10))
                          ->orWhere('phone', $phone)
                          ->orWhere('phone', '+91' . $phone);
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
    }
}
