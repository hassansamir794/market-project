<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderRequest;
use Illuminate\Http\Request;

class OrderRequestAdminController extends Controller
{
    private const STATUSES = [
        'new' => 'New',
        'confirmed' => 'Confirmed',
        'preparing' => 'Preparing',
        'ready' => 'Ready',
        'delivered' => 'Delivered',
        'canceled' => 'Canceled',
    ];

    public function index(Request $request)
    {
        $status = (string) $request->query('status', '');
        $q = trim((string) $request->query('q', ''));

        $ordersQuery = OrderRequest::with('product')
            ->when($status !== '', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', '%' . $q . '%')
                        ->orWhere('phone', 'like', '%' . $q . '%')
                        ->orWhere('note', 'like', '%' . $q . '%')
                        ->orWhere('admin_note', 'like', '%' . $q . '%')
                        ->orWhereHas('product', function ($productQuery) use ($q) {
                            $productQuery->where('name', 'like', '%' . $q . '%');
                        });
                });
            });

        $summary = [
            'all' => (clone $ordersQuery)->count(),
            'new' => (clone $ordersQuery)->where('status', 'new')->count(),
            'active' => (clone $ordersQuery)->whereIn('status', ['confirmed', 'preparing', 'ready'])->count(),
            'delivered' => (clone $ordersQuery)->where('status', 'delivered')->count(),
            'canceled' => (clone $ordersQuery)->where('status', 'canceled')->count(),
        ];

        $orders = $ordersQuery
            ->orderByRaw("case when status = 'new' then 0 when status = 'confirmed' then 1 when status = 'preparing' then 2 when status = 'ready' then 3 else 4 end")
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $statuses = self::STATUSES;

        return view('admin.order_requests.index', compact('orders', 'statuses', 'status', 'q', 'summary'));
    }

    public function update(Request $request, OrderRequest $orderRequest)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:' . implode(',', array_keys(self::STATUSES))],
            'admin_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $statusChanged = $validated['status'] !== $orderRequest->status;

        $orderRequest->update([
            'status' => $validated['status'],
            'admin_note' => $validated['admin_note'] ?? null,
            'admin_seen_at' => $orderRequest->admin_seen_at ?: now(),
            'status_updated_at' => $statusChanged ? now() : ($orderRequest->status_updated_at ?: now()),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Order updated.');
    }

    public function destroy(OrderRequest $orderRequest)
    {
        $orderRequest->delete();

        return redirect()
            ->back()
            ->with('success', 'Order request deleted.');
    }

    public function bulk(Request $request)
    {
        $validated = $request->validate([
            'action' => ['required', 'string', 'in:confirmed,preparing,ready,delivered,canceled,delete'],
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:order_requests,id'],
        ]);

        $query = OrderRequest::whereIn('id', $validated['ids']);

        if ($validated['action'] === 'delete') {
            $query->delete();
            $message = 'Order requests deleted.';
        } else {
            $query->update([
                'status' => $validated['action'],
                'admin_seen_at' => now(),
                'status_updated_at' => now(),
            ]);
            $message = 'Order requests updated.';
        }

        return redirect()
            ->back()
            ->with('success', $message);
    }
}
