@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h1 class="text-2xl font-bold">Dashboard</h1>
        <div class="grid grid-cols-2 sm:flex gap-2 sm:gap-3 w-full sm:w-auto">
            <a href="{{ route('admin.products.index') }}" class="btn-primary text-center">
                Manage Products
            </a>
            <a href="{{ route('admin.categories.index') }}" class="btn-outline text-center">
                Manage Categories
            </a>
            <a href="{{ route('admin.order-requests.index') }}" class="btn-outline text-center">
                Order Requests
            </a>
            <a href="{{ route('admin.reviews.index') }}" class="btn-outline text-center">
                Reviews
            </a>
            <a href="{{ route('admin.notifications.index') }}" class="btn-outline text-center">
                Notifications
                @if(($stats['unread_notifications'] ?? 0) > 0)
                    <span class="ml-1 inline-flex items-center justify-center rounded-full bg-red-600 text-white text-xs min-w-[20px] h-5 px-1">
                        {{ $stats['unread_notifications'] }}
                    </span>
                @endif
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 min-[420px]:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="glass-card p-4">
            <div class="text-sm text-gray-500">Products</div>
            <div class="text-2xl font-bold">{{ $stats['products'] }}</div>
        </div>
        <div class="glass-card p-4">
            <div class="text-sm text-gray-500">Categories</div>
            <div class="text-2xl font-bold">{{ $stats['categories'] }}</div>
        </div>
        <div class="glass-card p-4">
            <div class="text-sm text-gray-500">Order Requests</div>
            <div class="text-2xl font-bold">{{ $stats['orders'] }}</div>
        </div>
        <div class="glass-card p-4">
            <div class="text-sm text-gray-500">Reviews</div>
            <div class="text-2xl font-bold">{{ $stats['reviews'] }}</div>
        </div>
        <div class="glass-card p-4 min-[420px]:col-span-2 lg:col-span-4">
            <div class="text-sm text-gray-500">Unread Notifications</div>
            <div class="text-2xl font-bold">{{ $stats['unread_notifications'] }}</div>
            <a href="{{ route('admin.notifications.index') }}" class="mt-2 inline-flex text-sm font-semibold underline">
                Open notification center
            </a>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="glass-card p-5">
            <h2 class="text-lg font-semibold mb-4">Recent Order Requests</h2>
            @forelse($recentOrders as $order)
                <div class="border-b py-3">
                    <div class="font-semibold">{{ $order->name }} — {{ $order->phone }}</div>
                    <div class="text-sm text-gray-600">
                        Product: {{ $order->product?->name ?? 'N/A' }} | Qty: {{ $order->quantity }} | Status: {{ $order->status }}
                    </div>
                    @if($order->note)
                        <div class="text-sm text-gray-500 mt-1">{{ $order->note }}</div>
                    @endif
                </div>
            @empty
                <div class="text-gray-500">No order requests yet.</div>
            @endforelse
        </div>

        <div class="glass-card p-5">
            <h2 class="text-lg font-semibold mb-4">Top Viewed Products</h2>
            @forelse($topViewed as $product)
                <div class="border-b py-3 flex items-center justify-between">
                    <div class="font-semibold">{{ $product->name }}</div>
                    <div class="text-sm text-gray-600">{{ $product->views ?? 0 }} views</div>
                </div>
            @empty
                <div class="text-gray-500">No products yet.</div>
            @endforelse
        </div>
    </div>

    <div class="mt-8 glass-card p-5">
        <h2 class="text-lg font-semibold mb-4">Low Stock</h2>
        @forelse($lowStock as $product)
            <div class="border-b py-3 flex items-center justify-between">
                <div class="font-semibold">{{ $product->name }}</div>
                <div class="text-sm text-red-600 font-semibold">{{ $product->stock ?? 0 }}</div>
            </div>
        @empty
            <div class="text-gray-500">No low stock items.</div>
        @endforelse
    </div>

    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="glass-card p-5">
            <h2 class="text-lg font-semibold mb-4">Unread Order Notifications</h2>
            @forelse($latestUnreadOrders as $order)
                <div class="border-b py-3 flex items-start justify-between gap-3">
                    <div>
                        <div class="font-semibold">{{ $order->name }}</div>
                        <div class="text-sm text-gray-600">{{ $order->product?->name ?? 'N/A' }} | Qty: {{ $order->quantity }}</div>
                    </div>
                    <form method="POST" action="{{ route('admin.notifications.read', ['type' => 'order', 'id' => $order->id]) }}">
                        @csrf
                        <button class="text-sm font-semibold underline">Mark read</button>
                    </form>
                </div>
            @empty
                <div class="text-gray-500">No unread order notifications.</div>
            @endforelse
        </div>

        <div class="glass-card p-5">
            <h2 class="text-lg font-semibold mb-4">Unread Review Notifications</h2>
            @forelse($latestUnreadReviews as $review)
                <div class="border-b py-3 flex items-start justify-between gap-3">
                    <div>
                        <div class="font-semibold">{{ $review->name }} ({{ $review->rating }}/5)</div>
                        <div class="text-sm text-gray-600">{{ $review->product?->name ?? 'N/A' }}</div>
                    </div>
                    <form method="POST" action="{{ route('admin.notifications.read', ['type' => 'review', 'id' => $review->id]) }}">
                        @csrf
                        <button class="text-sm font-semibold underline">Mark read</button>
                    </form>
                </div>
            @empty
                <div class="text-gray-500">No unread review notifications.</div>
            @endforelse
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="glass-card p-5">
            <h2 class="text-lg font-semibold mb-1">Traffic Sources</h2>
            <p class="text-sm text-gray-600 mb-4">Last 30 days (since {{ $analyticsFrom->format('Y-m-d') }})</p>
            @forelse($trafficSources as $source)
                <div class="border-b py-3 flex items-center justify-between">
                    <div class="font-semibold capitalize">{{ $source->source }}</div>
                    <div class="text-sm text-gray-600">{{ number_format((int) $source->total) }} visits</div>
                </div>
            @empty
                <div class="text-gray-500">No traffic data yet.</div>
            @endforelse
        </div>

        <div class="glass-card p-5">
            <h2 class="text-lg font-semibold mb-1">Top Search Keywords</h2>
            <p class="text-sm text-gray-600 mb-4">Most used search terms</p>
            @forelse($topKeywords as $keyword)
                <div class="border-b py-3 flex items-center justify-between gap-3">
                    <div class="font-semibold break-all">{{ $keyword->keyword }}</div>
                    <div class="text-sm text-gray-600 whitespace-nowrap">{{ number_format((int) $keyword->count) }} searches</div>
                </div>
            @empty
                <div class="text-gray-500">No search keywords yet.</div>
            @endforelse
        </div>
    </div>
@endsection
