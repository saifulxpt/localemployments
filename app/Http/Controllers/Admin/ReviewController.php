<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::with('reviewer', 'reviewee', 'booking')
            ->latest()->paginate(20)->withQueryString();
        return view('admin.reviews.index', compact('reviews'));
    }

    public function hide(Review $review)
    {
        $review->update(['is_visible' => false]);
        return back()->with('success', 'রিভিউ লুকানো হয়েছে।');
    }

    public function showReview(Review $review)
    {
        $review->update(['is_visible' => true]);
        return back()->with('success', 'রিভিউ দৃশ্যমান করা হয়েছে।');
    }
}
