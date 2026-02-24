<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ReviewAdminController extends Controller
{
    public function index()
    {
        $reviews = Review::with('product')
            ->latest()
            ->paginate(15);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function update(Request $request, Review $review)
    {
        $validated = $request->validate([
            'is_approved' => ['required', 'boolean'],
        ]);

        $review->update([
            'is_approved' => (bool) $validated['is_approved'],
        ]);

        Cache::forget('product.show.' . $review->product_id);

        return redirect()
            ->back()
            ->with('success', 'Review updated.');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        Cache::forget('product.show.' . $review->product_id);

        return redirect()
            ->back()
            ->with('success', 'Review deleted.');
    }

    public function bulk(Request $request)
    {
        $validated = $request->validate([
            'action' => ['required', 'string', 'in:approve,hide,delete'],
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:reviews,id'],
        ]);

        $query = Review::whereIn('id', $validated['ids']);
        $productIds = $query->pluck('product_id')->unique();

        if ($validated['action'] === 'approve') {
            $query->update(['is_approved' => true]);
            $message = 'Reviews approved.';
        } elseif ($validated['action'] === 'hide') {
            $query->update(['is_approved' => false]);
            $message = 'Reviews hidden.';
        } else {
            $query->delete();
            $message = 'Reviews deleted.';
        }

        foreach ($productIds as $productId) {
            Cache::forget('product.show.' . $productId);
        }

        return redirect()
            ->back()
            ->with('success', $message);
    }
}
