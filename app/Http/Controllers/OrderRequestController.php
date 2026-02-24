<?php

namespace App\Http\Controllers;

use App\Models\OrderRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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

        $adminEmail = env('ADMIN_NOTIFY_EMAIL');
        if ($adminEmail) {
            $subject = 'New order request';
            $adminWhatsapp = env('ADMIN_WHATSAPP_NUMBER');
            $waLink = null;
            if ($adminWhatsapp) {
                $waMessage = urlencode(
                    "New order request\n"
                    . "Product: {$product->name} (ID: {$product->id})\n"
                    . "Name: {$validated['name']}\n"
                    . "Phone: {$validated['phone']}\n"
                    . "Quantity: {$validated['quantity']}\n"
                    . "Note: " . ($validated['note'] ?? '-')
                );
                $waLink = "https://wa.me/{$adminWhatsapp}?text={$waMessage}";
            }
            $body = "Product: {$product->name} (ID: {$product->id})\n"
                . "Name: {$validated['name']}\n"
                . "Phone: {$validated['phone']}\n"
                . "Quantity: {$validated['quantity']}\n"
                . "Note: " . ($validated['note'] ?? '-') . "\n"
                . "Admin: " . route('admin.order-requests.index')
                . ($waLink ? "\nWhatsApp: {$waLink}" : '');
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
            ->with('order_success', 'Order request submitted.')
            ->with('order_whatsapp_link', null);
    }
}
