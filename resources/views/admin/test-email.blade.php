@extends('layouts.admin')
@section('page_title', 'Test Email')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Test Email</h1>
        <p class="text-sm text-slate-500 mt-1">Verify that email sending is working correctly</p>
    </div>

    @if($success)
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-medium">
        ✓ {{ $success }}
    </div>
    @endif

    @if($error)
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
        <p class="font-bold">✗ Email Failed!</p>
        <p class="mt-1 text-xs font-mono break-all">{{ $error }}</p>
    </div>
    @endif

    <!-- Current Config -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <h3 class="text-sm font-bold text-slate-700 mb-3">Current Mail Configuration</h3>
        <div class="space-y-2">
            @foreach($config as $key => $value)
            <div class="flex items-center gap-3">
                <span class="text-xs font-mono text-slate-500 w-40">{{ $key }}</span>
                <span class="text-sm font-medium {{ $value === '***NOT SET***' ? 'text-red-600' : 'text-slate-700' }}">{{ $value ?: '(empty)' }}</span>
            </div>
            @endforeach
        </div>

        @if($config['MAIL_PASSWORD'] === '***NOT SET***')
        <div class="mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-xs">
            <strong>MAIL_PASSWORD is not set!</strong> Add it to your .env file:
            <code class="block mt-1 bg-red-100 px-2 py-1 rounded">MAIL_PASSWORD=your_shop_email_password</code>
        </div>
        @endif

        @if(!$config['MAIL_USERNAME'])
        <div class="mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-xs">
            <strong>MAIL_USERNAME is not set!</strong> Add it to your .env file:
            <code class="block mt-1 bg-red-100 px-2 py-1 rounded">MAIL_USERNAME=shop@theshivara.com</code>
        </div>
        @endif
    </div>

    <!-- Send Test -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <h3 class="text-sm font-bold text-slate-700 mb-3">Send Test Email</h3>
        <form action="{{ route('admin.test-email') }}" method="GET" class="flex items-end gap-3">
            <input type="hidden" name="send" value="1">
            <div class="flex-1">
                <label class="block text-xs font-semibold text-slate-500 mb-1">Send To</label>
                <input type="email" name="to" value="shop@theshivara.com" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-200">
            </div>
            <button type="submit" class="px-5 py-2.5 text-white text-xs font-bold uppercase rounded-lg hover:opacity-90 transition" style="background-color:#2C2418">Send Test</button>
        </form>
    </div>

    <!-- Required .env -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <h3 class="text-sm font-bold text-slate-700 mb-3">Required .env Settings</h3>
        <pre class="bg-slate-900 text-green-400 text-xs p-4 rounded-lg overflow-x-auto">MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=shop@theshivara.com
MAIL_PASSWORD=YourEmailPasswordHere
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=shop@theshivara.com
MAIL_FROM_NAME="Shivara"</pre>
        <p class="text-xs text-slate-500 mt-3">After updating .env, run: <code class="bg-slate-100 px-2 py-0.5 rounded">/opt/alt/php84/usr/bin/php artisan config:clear</code></p>
    </div>
</div>
@endsection
