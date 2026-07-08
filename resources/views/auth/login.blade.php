@extends('layouts.app')
@section('title', 'Login - Shivara')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center px-4 py-16">
    <div class="w-full max-w-md" x-data="phoneLogin()">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block">
                <img src="/public/shivaralogo.png" alt="Shivara" class="h-12 mx-auto" onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
                <span style="display:none" class="text-3xl font-display font-bold text-espresso-700">SHIVARA</span>
            </a>
            <p class="text-gray-500 text-sm mt-3">Login with your WhatsApp number</p>
        </div>

        <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm">
            {{-- Step 1: Enter Phone Number --}}
            <div x-show="step === 'phone'">
                <form method="POST" action="{{ route('login.send-otp') }}" @submit.prevent="sendOtp($event)">
                    @csrf
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">WhatsApp Number</label>
                        <div class="flex gap-2">
                            <div class="flex items-center px-3 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-600 font-medium">
                                +91
                            </div>
                            <input type="tel" name="phone" x-model="phone" required maxlength="10" pattern="[0-9]{10}"
                                   class="flex-1 px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gold-200 focus:border-gold-300 focus:bg-white transition placeholder:text-gray-400"
                                   placeholder="Enter 10-digit number">
                        </div>
                        <p class="text-[11px] text-gray-400 mt-1.5">We'll send a verification code to this number</p>
                        @error('phone')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" :disabled="loading"
                            class="w-full px-6 py-3.5 text-white font-semibold rounded-xl hover:opacity-90 transition shadow-lg flex items-center justify-center gap-2"
                            style="background-color:#2C2418">
                        <svg x-show="!loading" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        <svg x-show="loading" x-cloak class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        <span x-text="loading ? 'Sending...' : 'Send OTP via WhatsApp'"></span>
                    </button>
                </form>

                <p x-show="errorMsg" x-text="errorMsg" class="text-red-500 text-xs text-center mt-3"></p>
            </div>

            {{-- Step 2: Verify OTP --}}
            <div x-show="step === 'otp'" x-cloak>
                <div class="text-center mb-5">
                    <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-3" style="background-color: #f0fdf4;">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <p class="text-sm text-gray-600">OTP sent to <span class="font-bold" x-text="'+91 ' + phone"></span></p>
                    <button @click="step = 'phone'" class="text-xs text-gold-600 mt-1 hover:underline">Change number</button>
                </div>

                <form method="POST" action="{{ route('login.verify-otp') }}" @submit.prevent="verifyOtp($event)">
                    @csrf
                    <input type="hidden" name="phone" :value="phone">
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5 text-center">Enter 4-digit OTP</label>
                        <div class="flex justify-center gap-3">
                            <template x-for="(digit, i) in otp" :key="i">
                                <input type="text" maxlength="1" x-model="otp[i]"
                                       @input="if(otp[i]) { if(i < 3) $el.nextElementSibling?.focus(); }"
                                       @keydown.backspace="if(!otp[i] && i > 0) { $el.previousElementSibling?.focus(); }"
                                       class="w-12 h-14 text-center text-xl font-bold bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gold-200 focus:border-gold-300 focus:bg-white transition">
                            </template>
                        </div>
                        @error('otp')<p class="text-red-500 text-xs mt-1.5 text-center">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" :disabled="loading || otp.join('').length < 4"
                            class="w-full px-6 py-3.5 text-white font-semibold rounded-xl hover:opacity-90 transition shadow-lg disabled:opacity-50"
                            style="background-color:#2C2418">
                        <span x-text="loading ? 'Verifying...' : 'Verify & Login'"></span>
                    </button>
                </form>

                <p x-show="errorMsg" x-text="errorMsg" class="text-red-500 text-xs text-center mt-3"></p>

                <div class="text-center mt-4">
                    <button @click="resendOtp()" :disabled="resendTimer > 0" class="text-xs text-gray-500 hover:text-gold-600 disabled:opacity-50">
                        <span x-show="resendTimer > 0" x-text="'Resend in ' + resendTimer + 's'"></span>
                        <span x-show="resendTimer <= 0">Resend OTP</span>
                    </button>
                </div>
            </div>
        </div>

        <p class="text-center text-[11px] text-gray-400 mt-6">
            By continuing, you agree to our <a href="{{ route('terms') }}" class="underline">Terms</a> & <a href="{{ route('privacy-policy') }}" class="underline">Privacy Policy</a>
        </p>
    </div>
</div>

<script>
function phoneLogin() {
    return {
        step: 'phone',
        phone: '',
        otp: ['', '', '', ''],
        loading: false,
        errorMsg: '',
        resendTimer: 0,

        async sendOtp(e) {
            if (this.phone.length !== 10) { this.errorMsg = 'Please enter a valid 10-digit number'; return; }
            this.loading = true;
            this.errorMsg = '';
            try {
                const res = await fetch('{{ route("login.send-otp") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ phone: this.phone })
                });
                const data = await res.json();
                if (data.success) {
                    this.step = 'otp';
                    this.startResendTimer();
                } else {
                    this.errorMsg = data.message || 'Failed to send OTP';
                }
            } catch(err) {
                this.errorMsg = 'Something went wrong. Please try again.';
            }
            this.loading = false;
        },

        async verifyOtp(e) {
            const code = this.otp.join('');
            if (code.length < 4) return;
            this.loading = true;
            this.errorMsg = '';
            try {
                const res = await fetch('{{ route("login.verify-otp") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ phone: this.phone, otp: code })
                });
                const data = await res.json();
                if (data.success) {
                    window.location.href = data.redirect || '/';
                } else {
                    this.errorMsg = data.message || 'Invalid OTP';
                    this.otp = ['', '', '', ''];
                }
            } catch(err) {
                this.errorMsg = 'Something went wrong. Please try again.';
            }
            this.loading = false;
        },

        async resendOtp() {
            this.otp = ['', '', '', ''];
            await this.sendOtp();
            this.startResendTimer();
        },

        startResendTimer() {
            this.resendTimer = 30;
            const interval = setInterval(() => {
                this.resendTimer--;
                if (this.resendTimer <= 0) clearInterval(interval);
            }, 1000);
        }
    }
}
</script>
@endsection
