@extends('layouts.app')
@section('title', 'About Shivara | Premium Ayurvedic Wellness Brand')
@section('meta_description', 'Learn about Shivara, a premium Ayurvedic wellness brand built on decades of formulation expertise, quality manufacturing, and authentic ingredients.')

@section('content')
<div class="bg-cream-50">

    <!-- Page Title -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 pt-10 pb-4">
        <h1 class="font-display text-4xl md:text-5xl lg:text-6xl font-bold text-espresso-700">ABOUT US</h1>
    </div>

    <!-- Hero Banner Image (1200x600) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 mb-12">
        <div class="w-full rounded-2xl overflow-hidden shadow-lg">
            <img src="/public/aboutusup.jpg" alt="Shivara - Premium Ayurvedic Wellness" class="w-full h-auto object-cover" style="max-height:600px;" loading="eager">
        </div>
    </div>

    <!-- Intro Section: Large text left + smaller text right -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 pb-16">
        <div class="grid md:grid-cols-2 gap-8 md:gap-16 items-start">
            <div>
                <h2 class="font-display text-2xl md:text-3xl lg:text-4xl font-bold text-espresso-700 leading-tight">
                    Shivara was founded with a simple belief: authentic Ayurveda deserves uncompromising quality, complete transparency, and formulations crafted for contemporary lifestyles.
                </h2>
            </div>
            <div class="pt-2">
                <p class="text-espresso-500 text-sm md:text-base leading-relaxed">
                    We do not believe in shortcuts, synthetic fillers, or fleeting wellness trends. Instead, we create targeted, premium Ayurvedic formulations designed to integrate seamlessly into your daily routine—bringing balance, vitality, and strength to everyday life.
                </p>
            </div>
        </div>
    </div>

    <!-- Two Column: Heritage + Image -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 pb-16">
        <div class="grid md:grid-cols-2 gap-8 md:gap-12">
            <!-- Left: Text Content -->
            <div class="space-y-8">
                <div class="border-t border-espresso-200 pt-6">
                    <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-espresso-400 mb-3">Decades of Expertise</h3>
                    <h4 class="font-display text-xl md:text-2xl font-bold text-espresso-700 mb-4">Built on More Than Two Decades of Ayurvedic Formulation & Manufacturing Expertise</h4>
                    <p class="text-espresso-500 text-sm leading-relaxed mb-4">
                        For over twenty years, we have dedicated ourselves to understanding Ayurvedic formulation, ingredient quality, and manufacturing excellence. Shivara is the natural evolution of that expertise—created to bring authentic Ayurveda into modern everyday life without compromise.
                    </p>
                    <p class="text-espresso-500 text-sm leading-relaxed">
                        When you choose Shivara, you are choosing decades of formulation expertise, uncompromising quality standards, and time-tested Ayurvedic knowledge.
                    </p>
                </div>

                <div class="border-t border-espresso-200 pt-6">
                    <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-espresso-400 mb-3">Our Philosophy</h3>
                    <p class="text-espresso-500 text-sm leading-relaxed">
                        We believe Ayurveda should be easy to trust, effortless to incorporate into daily life, and uncompromising in quality. Every Shivara formulation is created with this philosophy at its core—combining authentic Ayurvedic wisdom with manufacturing excellence to create products that fit naturally into your routine.
                    </p>
                </div>
            </div>

            <!-- Right: Image -->
            <div class="rounded-2xl overflow-hidden shadow-lg">
                <img src="/public/aboutusdown.jpg" alt="Shivara Quality & Heritage" class="w-full h-full object-cover" style="min-height:400px;" loading="lazy">
            </div>
        </div>
    </div>

    <!-- Core Values Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 pb-16">
        <div class="grid md:grid-cols-2 gap-8 md:gap-12">
            <div class="border-t border-espresso-200 pt-6">
                <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-espresso-400 mb-3">Our Core Values</h3>
                <div class="space-y-6">
                    <div>
                        <h4 class="font-display text-xl md:text-2xl font-bold text-espresso-700 mb-3">
                            At Shivara, authenticity is at the heart of what we do. We believe wellness should be effortless and trustworthy for everyone.
                        </h4>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <span class="w-2 h-2 rounded-full bg-gold-500 mt-2 flex-shrink-0"></span>
                            <p class="text-espresso-500 text-sm leading-relaxed"><strong class="text-espresso-700">Authenticity:</strong> We stay true to traditional Ayurvedic principles and formulation knowledge.</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="w-2 h-2 rounded-full bg-gold-500 mt-2 flex-shrink-0"></span>
                            <p class="text-espresso-500 text-sm leading-relaxed"><strong class="text-espresso-700">Quality:</strong> Every formulation is crafted with rigorous quality standards to ensure consistency, purity, and safety.</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="w-2 h-2 rounded-full bg-gold-500 mt-2 flex-shrink-0"></span>
                            <p class="text-espresso-500 text-sm leading-relaxed"><strong class="text-espresso-700">Transparency:</strong> Honest ingredients, clean formulations, and clear communication.</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="w-2 h-2 rounded-full bg-gold-500 mt-2 flex-shrink-0"></span>
                            <p class="text-espresso-500 text-sm leading-relaxed"><strong class="text-espresso-700">Integrity:</strong> No shortcuts, no hidden fillers, and no unnecessary compromises.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-espresso-200 pt-6">
                <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-espresso-400 mb-3">Quality Standards</h3>
                <div class="space-y-5">
                    <div>
                        <h5 class="text-sm font-bold text-espresso-700 mb-1">Principle-Guided Formulations</h5>
                        <p class="text-espresso-500 text-sm leading-relaxed">Every formulation is developed with a deep understanding of Ayurvedic principles and carefully selected botanical ingredients.</p>
                    </div>
                    <div>
                        <h5 class="text-sm font-bold text-espresso-700 mb-1">Premium Ingredients</h5>
                        <p class="text-espresso-500 text-sm leading-relaxed">We carefully source high-quality botanicals and raw ingredients from trusted suppliers to preserve their natural integrity and effectiveness.</p>
                    </div>
                    <div>
                        <h5 class="text-sm font-bold text-espresso-700 mb-1">GMP & AYUSH Standards</h5>
                        <p class="text-espresso-500 text-sm leading-relaxed">Manufactured in GMP-certified facilities in strict accordance with AYUSH quality standards, ensuring batch-to-batch consistency, purity, and safety.</p>
                    </div>
                    <div>
                        <h5 class="text-sm font-bold text-espresso-700 mb-1">Uncompromising Integrity</h5>
                        <p class="text-espresso-500 text-sm leading-relaxed">Thoughtfully formulated without unnecessary fillers or artificial additives.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Why Choose Shivara - Full Width CTA Section -->
    <div class="border-t border-espresso-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-16">
            <div class="text-center max-w-3xl mx-auto">
                <div class="w-10 h-10 mx-auto mb-4 flex items-center justify-center">
                    <svg class="w-6 h-6 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                </div>
                <p class="text-xs font-bold uppercase tracking-[0.3em] text-espresso-400 mb-6">Why Choose Shivara</p>
                <h2 class="font-display text-2xl md:text-3xl lg:text-4xl font-bold text-espresso-700 leading-tight mb-6">
                    At Shivara, we don't believe wellness should rely on trends or shortcuts. We believe it begins with authentic ingredients, thoughtful formulations, and uncompromising quality.
                </h2>
                <p class="text-espresso-500 text-sm md:text-base leading-relaxed mb-8">
                    Every product we create reflects our commitment to helping you embrace Ayurveda with confidence, every single day. We refrain from exaggerated promises or unverified claims—offering instead a steadfast commitment to delivering authentic, premium Ayurvedic products crafted to the highest quality standards.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('products.index') }}" class="inline-block px-8 py-3.5 text-white font-semibold rounded-xl hover:opacity-90 transition shadow-lg text-sm" style="background-color:#2C2418">
                        Discover Our Formulations
                    </a>
                    <a href="{{ route('products.index') }}" class="inline-block px-8 py-3.5 text-espresso-700 font-semibold rounded-xl border-2 border-espresso-200 hover:border-espresso-400 hover:bg-espresso-50 transition text-sm">
                        Explore the Collection
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact CTA -->
    <div class="bg-espresso-700 py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 text-center">
            <p class="text-cream-200 text-sm mb-2">Have questions about our products?</p>
            <h3 class="font-display text-2xl md:text-3xl font-bold text-cream-50 mb-6">Start your Ayurvedic wellness journey today.</h3>
            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 text-cream-50 font-semibold text-sm hover:text-gold-300 transition">
                CONTACT US
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>

</div>
@endsection
