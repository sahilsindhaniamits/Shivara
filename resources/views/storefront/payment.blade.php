@extends('layouts.app')
@section('title', 'Payment - Shivara')

@section('content')
<div class="max-w-xl mx-auto px-4 py-20 text-center">
    <div class="bg-white p-8 rounded-2xl border border-border">
        <h1 class="text-2xl font-bold text-gray-900 mb-4">Complete Payment</h1>
        <p class="text-gray-500 mb-6">Order: {{ $order->order_number }}</p>
        <p class="text-3xl font-bold text-primary mb-8">₹{{ number_format($order->total_amount) }}</p>
        <button id="pay-btn" class="w-full px-8 py-4 bg-primary text-white font-medium rounded-xl hover:bg-primary-dark transition text-lg">
            Pay with Razorpay
        </button>
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
        prefill: { email: '{{ auth()->user()->email }}', contact: '{{ auth()->user()->phone }}' },
        theme: { color: '#B7925C' }
    };
    var rzp = new Razorpay(options);
    rzp.open();
});
</script>
@endpush
@endsection
