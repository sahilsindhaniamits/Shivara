<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['product', 'user'])->orderBy('created_at', 'desc');
        if ($request->filled('status')) {
            $query->where('is_approved', $request->status === 'approved');
        }
        $reviews = $query->paginate(20);
        return view('admin.reviews.index', compact('reviews'));
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
