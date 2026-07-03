@extends('layouts.app')
@section('title', 'Contact Us - Shivara')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-16">
    <div class="text-center mb-14">
        <p class="section-label mb-4">Get in Touch</p>
        <h1 class="font-editorial text-3xl md:text-5xl text-secondary">We'd love to hear from you.</h1>
    </div>

    <div class="grid md:grid-cols-2 gap-12">
        <!-- Contact Info -->
        <div class="space-y-8">
            <div>
                <h3 class="font-semibold text-gray-900 mb-4">Contact Information</h3>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <i data-lucide="map-pin" class="w-5 h-5 text-primary shrink-0 mt-0.5"></i>
                        <span class="text-gray-600">{{ config('shivara.address.line1') }}, {{ config('shivara.address.line2') }}, {{ config('shivara.address.city') }}, {{ config('shivara.address.state') }} - {{ config('shivara.address.pincode') }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i data-lucide="phone" class="w-5 h-5 text-primary shrink-0"></i>
                        <a href="tel:{{ config('shivara.phone') }}" class="text-gray-600 hover:text-primary">{{ config('shivara.phone') }}</a>
                    </div>
                    <div class="flex items-center gap-3">
                        <i data-lucide="mail" class="w-5 h-5 text-primary shrink-0"></i>
                        <a href="mailto:{{ config('shivara.email') }}" class="text-gray-600 hover:text-primary">{{ config('shivara.email') }}</a>
                    </div>
                </div>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900 mb-4">Business Hours</h3>
                <p class="text-gray-600">Monday - Saturday: 9:00 AM - 6:00 PM IST</p>
                <p class="text-gray-600">Sunday: Closed</p>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="bg-white p-8 rounded-2xl border border-border">
            <form class="space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Name</label>
                        <input type="text" class="w-full px-4 py-3 rounded-xl border border-border text-sm focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone</label>
                        <input type="text" class="w-full px-4 py-3 rounded-xl border border-border text-sm focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <input type="email" class="w-full px-4 py-3 rounded-xl border border-border text-sm focus:outline-none focus:ring-2 focus:ring-primary/20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Subject</label>
                    <input type="text" class="w-full px-4 py-3 rounded-xl border border-border text-sm focus:outline-none focus:ring-2 focus:ring-primary/20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Message</label>
                    <textarea rows="4" class="w-full px-4 py-3 rounded-xl border border-border text-sm focus:outline-none focus:ring-2 focus:ring-primary/20"></textarea>
                </div>
                <button type="button" class="w-full px-6 py-3 bg-primary text-white font-medium rounded-xl hover:bg-primary-dark transition">Send Message</button>
            </form>
        </div>
    </div>
</div>
@endsection
