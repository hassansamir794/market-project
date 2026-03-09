<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderRequest;
use Illuminate\Http\Request;

class OrderRequestAdminController extends Controller
{
    private const STATUSES = ['new', 'contacted', 'completed', 'canceled'];

    public function index()
    {
        $orders = OrderRequest::with('product')
            ->latest()
            ->paginate(15);

        $statuses = self::STATUSES;

        return view('admin.order_requests.index', compact('orders', 'statuses'));
    }

    public function update(Request $request, OrderRequest $orderRequest)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:' . implode(',', self::STATUSES)],
        ]);

        $orderRequest->update([
            'status' => $validated['status'],
            'admin_seen_at' => $orderRequest->admin_seen_at ?: now(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Order status updated.');
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
            'action' => ['required', 'string', 'in:contacted,completed,canceled,delete'],
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
            ]);
            $message = 'Order requests updated.';
        }

        return redirect()
            ->back()
            ->with('success', $message);
    }
}
