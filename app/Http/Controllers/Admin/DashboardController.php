<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\OrderRequest;
use App\Models\Product;
use App\Models\Review;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products' => Product::count(),
            'categories' => Category::count(),
            'orders' => OrderRequest::count(),
            'reviews' => Review::count(),
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

        return view('admin.dashboard', compact('stats', 'recentOrders', 'topViewed', 'lowStock'));
    }
}
