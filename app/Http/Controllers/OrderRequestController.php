<?php

namespace App\Http\Controllers;

use App\Jobs\SendAdminOrderRequestNotificationsJob;
use App\Models\OrderRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderRequestController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
            'quantity' => ['required', 'integer', 'min:1', 'max:999'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $validated['product_id'] = $product->id;
        $validated['status'] = 'new';

        OrderRequest::create($validated);

        SendAdminOrderRequestNotificationsJob::dispatch(
            $product->id,
            $product->name,
            $validated['name'],
            $validated['phone'],
            (int) $validated['quantity'],
            $validated['note'] ?? null
        );

        return redirect()
            ->back()
            ->with('order_success', 'Order request submitted.')
            ->with('order_whatsapp_link', null);
    }
}
