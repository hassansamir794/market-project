<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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

        $adminEmail = env('ADMIN_NOTIFY_EMAIL');
        if ($adminEmail) {
            $subject = 'New review submitted';
            $body = "Product: {$product->name} (ID: {$product->id})\n"
                . "Name: {$validated['name']}\n"
                . "Rating: {$validated['rating']}\n"
                . "Comment: " . ($validated['comment'] ?? '-') . "\n"
                . "Admin: " . route('admin.reviews.index');
            try {
                Mail::raw($body, function ($message) use ($adminEmail, $subject) {
                    $message->to($adminEmail)->subject($subject);
                });
            } catch (\Throwable $e) {
                // ignore notification errors
            }
        }

        return redirect()
            ->back()
            ->with('review_success', 'Review submitted.');
    }
}
