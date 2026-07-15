@extends('layouts.app')
@section('title', 'Payment - Shivara')

@section('content')
<div class="max-w-xl mx-auto px-4 py-16 text-center">
    <div class="bg-white p-8 rounded-2xl border" style="border-color:rgba(183,146,92,0.15);">
        <div class="w-14 h-14 mx-auto mb-4 rounded-full flex items-center justify-center" style="background-color:rgba(183,146,92,0.1);">
            <svg class="w-7 h-7" style="color:#B7925C;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        </div>
        <h1 class="text-xl font-bold mb-2" style="color:#2C2418;">Complete Payment</h1>
        <p class="text-sm text-gray-500 mb-1">Order: {{ $order->order_number }}</p>
        <p class="text-3xl font-bold mb-6" style="color:#2C2418;">₹{{ number_format($order->total_amount, 2) }}</p>
        <button id="pay-btn" class="w-full px-8 py-4 text-white font-bold text-sm uppercase tracking-wider rounded-xl hover:opacity-90 transition" style="background-color:#2C2418;">
            Pay Securely
        </button>
        <p class="text-[10px] text-gray-400 mt-3">Secured by Razorpay • UPI, Cards, Net Banking, Wallets</p>
    </div>
</div>

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.getElementById('pay-btn').addEventListener('click', function() {
    var options = {
        key: '{{ $razorpayKey }}',
        amount: {{ $amount }},
        currency: 'INR',
        name: 'Shivara',
        description: 'Order {{ $order->order_number }}',
        image: '{{ url("/public/shivaralogo1.png") }}',
        order_id: '{{ $razorpayOrderId }}',
        handler: function(response) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("payment.verify") }}';
            var fields = {
                _token: '{{ csrf_token() }}',
                razorpay_order_id: response.razorpay_order_id,
                razorpay_payment_id: response.razorpay_payment_id,
                razorpay_signature: response.razorpay_signature
            };
            for (var key in fields) {
                var input = document.createElement('input');
                input.type = 'hidden'; input.name = key; input.value = fields[key];
                form.appendChild(input);
            }
            document.body.appendChild(form);
            form.submit();
        },
        prefill: {
            name: '{{ $order->address->full_name ?? auth()->user()->name }}',
            email: '{{ auth()->user()->email }}',
            contact: '{{ $order->address->phone ?? auth()->user()->phone }}'
        },
        notes: {
            order_number: '{{ $order->order_number }}',
            customer_id: '{{ auth()->id() }}'
        },
        theme: { color: '#2C2418' },
        modal: { ondismiss: function() { /* User closed payment modal */ } }
    };
    var rzp = new Razorpay(options);
    rzp.on('payment.failed', function(response) {
        alert('Payment failed. Please try again or choose a different payment method.');
    });
    rzp.open();
});
// Auto-open payment on page load
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() { document.getElementById('pay-btn').click(); }, 500);
});
</script>
@endpush
@endsection
