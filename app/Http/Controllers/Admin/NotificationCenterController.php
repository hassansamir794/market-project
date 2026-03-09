<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderRequest;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotificationCenterController extends Controller
{
    public function index()
    {
        $unreadOrders = OrderRequest::with('product')
            ->whereNull('admin_seen_at')
            ->latest()
            ->take(20)
            ->get();

        $unreadReviews = Review::with('product')
            ->whereNull('admin_seen_at')
            ->latest()
            ->take(20)
            ->get();

        $recentOrders = OrderRequest::with('product')
            ->latest()
            ->take(20)
            ->get();

        $recentReviews = Review::with('product')
            ->latest()
            ->take(20)
            ->get();

        $unreadOrdersCount = OrderRequest::whereNull('admin_seen_at')->count();
        $unreadReviewsCount = Review::whereNull('admin_seen_at')->count();

        return view('admin.notifications.index', compact(
            'unreadOrders',
            'unreadReviews',
            'recentOrders',
            'recentReviews',
            'unreadOrdersCount',
            'unreadReviewsCount'
        ));
    }

    public function markRead(Request $request, string $type, int $id): RedirectResponse
    {
        if ($type === 'order') {
            $notification = OrderRequest::findOrFail($id);
        } else {
            $notification = Review::findOrFail($id);
        }

        if (! $notification->admin_seen_at) {
            $notification->update(['admin_seen_at' => now()]);
        }

        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        $type = (string) $request->input('type', 'all');

        if ($type === 'order') {
            OrderRequest::whereNull('admin_seen_at')->update(['admin_seen_at' => now()]);
        } elseif ($type === 'review') {
            Review::whereNull('admin_seen_at')->update(['admin_seen_at' => now()]);
        } else {
            OrderRequest::whereNull('admin_seen_at')->update(['admin_seen_at' => now()]);
            Review::whereNull('admin_seen_at')->update(['admin_seen_at' => now()]);
        }

        return redirect()->back()->with('success', 'Notifications updated.');
    }
}
