<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
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

        return view('admin.reviews.index', compact('reviews', 'totalCount', 'pendingCount', 'approvedCount'));
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
