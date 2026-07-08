<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Send OTP to WhatsApp number
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|digits:10',
        ]);

        $phone = $request->phone;

        // Rate limit: max 5 OTPs per phone per hour
        $rateLimitKey = 'otp_rate_' . $phone;
        $attempts = Cache::get($rateLimitKey, 0);
        if ($attempts >= 5) {
            return response()->json(['success' => false, 'message' => 'Too many attempts. Try again later.']);
        }

        // Generate 4-digit OTP
        $otp = str_pad(random_int(1000, 9999), 4, '0', STR_PAD_LEFT);

        // Store OTP in cache for 5 minutes
        Cache::put('otp_' . $phone, $otp, now()->addMinutes(5));
        Cache::put($rateLimitKey, $attempts + 1, now()->addHour());

        // Send OTP via WhatsApp (using simple log for now - integrate with actual API later)
        // For production: integrate with Twilio/MSG91/Interakt WhatsApp Business API
        \Log::info("OTP for {$phone}: {$otp}");

        // If Interakt or MSG91 API is configured, send real WhatsApp message
        $this->sendWhatsAppOtp($phone, $otp);

        return response()->json(['success' => true, 'message' => 'OTP sent successfully']);
    }

    /**
     * Verify OTP and login/register the user
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|digits:10',
            'otp' => 'required|digits:4',
        ]);

        $phone = $request->phone;
        $storedOtp = Cache::get('otp_' . $phone);

        if (!$storedOtp || $storedOtp !== $request->otp) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired OTP']);
        }

        // OTP verified - clear it
        Cache::forget('otp_' . $phone);

        // Find or create user by phone
        $user = User::where('phone', $phone)->first();

        if (!$user) {
            // Auto-register new user with phone number
            $user = User::create([
                'phone' => $phone,
                'name' => null, // Will ask to complete profile later
                'role' => 'customer',
                'is_active' => true,
            ]);
        }

        if (!$user->is_active) {
            return response()->json(['success' => false, 'message' => 'Account is disabled. Contact support.']);
        }

        // Login the user
        Auth::login($user, true);
        $request->session()->regenerate();

        $redirect = $user->isAdmin() ? '/admin/dashboard' : '/';

        return response()->json(['success' => true, 'redirect' => $redirect]);
    }

    /**
     * Send WhatsApp OTP via API
     * Currently logs OTP - replace with actual WhatsApp Business API integration
     */
    private function sendWhatsAppOtp(string $phone, string $otp): void
    {
        // MSG91 WhatsApp OTP (if configured)
        $authKey = config('services.msg91.auth_key');
        $templateId = config('services.msg91.template_id');

        if ($authKey && $templateId) {
            try {
                $client = new \GuzzleHttp\Client();
                $client->post('https://control.msg91.com/api/v5/otp', [
                    'headers' => ['authkey' => $authKey, 'Content-Type' => 'application/json'],
                    'json' => [
                        'template_id' => $templateId,
                        'mobile' => '91' . $phone,
                        'otp' => $otp,
                    ]
                ]);
            } catch (\Exception $e) {
                \Log::error('WhatsApp OTP send failed: ' . $e->getMessage());
            }
            return;
        }

        // Fallback: Simple SMS via any configured gateway
        // For development: OTP is logged (check storage/logs/laravel.log)
    }

    /**
     * Legacy email login (kept for admin)
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
