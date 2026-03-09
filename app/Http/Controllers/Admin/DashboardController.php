<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\OrderRequest;
use App\Models\Product;
use App\Models\Review;
use App\Models\SearchKeyword;
use App\Models\TrafficVisit;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products' => Product::count(),
            'categories' => Category::count(),
            'orders' => OrderRequest::count(),
            'reviews' => Review::count(),
            'unread_notifications' => OrderRequest::whereNull('admin_seen_at')->count() + Review::whereNull('admin_seen_at')->count(),
        ];

        $recentOrders = OrderRequest::with('product')
            ->latest()
            ->take(8)
            ->get();

        $topViewed = Product::orderBy('views', 'desc')
            ->take(6)
            ->get();

        $lowStock = Product::where('stock', '<=', 5)
            ->orderBy('stock', 'asc')
            ->take(6)
            ->get();

        $latestUnreadOrders = OrderRequest::with('product')
            ->whereNull('admin_seen_at')
            ->latest()
            ->take(5)
            ->get();

        $latestUnreadReviews = Review::with('product')
            ->whereNull('admin_seen_at')
            ->latest()
            ->take(5)
            ->get();

        $analyticsFrom = now()->subDays(30);

        $trafficSources = collect();
        if (Schema::hasTable('traffic_visits')) {
            $trafficSources = TrafficVisit::selectRaw('source, COUNT(*) as total')
                ->where('created_at', '>=', $analyticsFrom)
                ->groupBy('source')
                ->orderByDesc('total')
                ->get();
        }

        $topKeywords = collect();
        if (Schema::hasTable('search_keywords')) {
            $topKeywords = SearchKeyword::orderByDesc('count')
                ->orderByDesc('last_searched_at')
                ->take(10)
                ->get();
        }

        return view('admin.dashboard', compact(
            'stats',
            'recentOrders',
            'topViewed',
            'lowStock',
            'latestUnreadOrders',
            'latestUnreadReviews',
            'trafficSources',
            'topKeywords',
            'analyticsFrom'
        ));
    }
}
