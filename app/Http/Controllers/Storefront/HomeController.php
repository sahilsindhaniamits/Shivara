<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Banner;
use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    public function index()
    {
        // Load up to 2 products per category + fill remaining spots for 'All' tab
        $categories = Category::active()
            ->withCount(['products' => fn($q) => $q->where('is_active', true)])
            ->orderBy('sort_order')
            ->get();

        $featuredProducts = Product::active()
            ->with(['primaryImage', 'images', 'category', 'variants'])
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->take(32); // Load enough to cover all categories

        $banners = Banner::active()->orderBy('sort_order')->get();

        return view('storefront.home', compact('featuredProducts', 'categories', 'banners'));
    }

    public function contact()
    {
        return view('storefront.contact');
    }

    public function about()
    {
        return view('storefront.pages.about');
    }

    /**
     * Handle contact form submission - send email to Contact@theshivara.com
     */
    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:100',
            'message' => 'required|string|max:5000',
        ]);

        $contactEmail = config('shivara.contact_email', 'Contact@theshivara.com');

        try {
            Mail::raw(
                "New Contact Form Inquiry\n\n" .
                "Name: {$request->name}\n" .
                "Phone: {$request->phone}\n" .
                "Email: {$request->email}\n" .
                "Subject: " . ($request->subject ?: 'General') . "\n\n" .
                "Message:\n{$request->message}",
                function ($mail) use ($request, $contactEmail) {
                    $mail->to($contactEmail)
                         ->subject('Contact Form: ' . ($request->subject ?: 'General Inquiry') . ' - ' . $request->name)
                         ->replyTo($request->email, $request->name);
                }
            );
        } catch (\Exception $e) {
            \Log::warning('Contact form email failed: ' . $e->getMessage());
        }

        return redirect()->route('contact')->with('success', 'Thank you! Your message has been sent. We\'ll get back to you within 24 hours.');
    }

    /**
     * Handle newsletter subscription
     */
    public function subscribeNewsletter(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        Newsletter::firstOrCreate(
            ['email' => strtolower($request->email)],
            ['subscribed_at' => now()]
        );

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Successfully subscribed!']);
        }

        return back()->with('success', 'Thank you for subscribing!');
    }
}
