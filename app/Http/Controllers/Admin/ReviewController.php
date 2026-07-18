<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['product', 'user']);

        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->where('is_approved', false);
            } elseif ($request->status === 'approved') {
                $query->where('is_approved', true);
            }
        }

        $reviews = $query->orderBy('created_at', 'desc')->paginate(20);

        // Count stats for tabs
        $totalCount = Review::count();
        $pendingCount = Review::where('is_approved', false)->count();
        $approvedCount = Review::where('is_approved', true)->count();

        // Products for add review form
        $products = Product::where('is_active', true)->orderBy('name')->get(['id', 'name']);

        return view('admin.reviews.index', compact('reviews', 'totalCount', 'pendingCount', 'approvedCount', 'products'));
    }

    /**
     * Admin manually adds a review
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'reviewer_name' => 'required|string|max:255',
            'reviewer_email' => 'nullable|email|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:5000',
            'review_images.*' => 'nullable|image|max:5120',
        ]);

        $images = [];
        if ($request->hasFile('review_images')) {
            foreach ($request->file('review_images') as $file) {
                $path = $file->store('reviews', 'public');
                $images[] = '/storage/' . $path;
            }
        }

        // Create a user record or use a placeholder user_id
        $user = \App\Models\User::firstOrCreate(
            ['email' => $request->reviewer_email ?: 'admin-review-' . time() . '@theshivara.com'],
            ['name' => $request->reviewer_name, 'password' => bcrypt(\Str::random(16))]
        );

        Review::create([
            'product_id' => $request->product_id,
            'user_id' => $user->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'images' => !empty($images) ? $images : null,
            'is_approved' => true, // Admin-added reviews are auto-approved
            'is_verified' => true,
        ]);

        return back()->with('success', 'Review added successfully!');
    }

    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);
        return back()->with('success', 'Review approved.');
    }

    public function decline(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Review declined and deleted.');
    }
}
