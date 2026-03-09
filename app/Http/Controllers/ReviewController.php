<?php

namespace App\Http\Controllers;

use App\Jobs\SendAdminReviewNotificationsJob;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $product->reviews()->create($validated);

        Cache::forget('product.show.' . $product->id);

        SendAdminReviewNotificationsJob::dispatch(
            $product->id,
            $product->name,
            $validated['name'],
            (int) $validated['rating'],
            $validated['comment'] ?? null
        );

        return redirect()
            ->back()
            ->with('review_success', 'Review submitted.');
    }
}
