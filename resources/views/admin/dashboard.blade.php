@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h1 class="text-2xl font-bold">Dashboard</h1>
        <div class="flex gap-3 flex-wrap">
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 rounded-xl bg-black text-white font-semibold">
                Manage Products
            </a>
            <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 rounded-xl border font-semibold">
                Manage Categories
            </a>
            <a href="{{ route('admin.order-requests.index') }}" class="px-4 py-2 rounded-xl border font-semibold">
                Order Requests
            </a>
            <a href="{{ route('admin.reviews.index') }}" class="px-4 py-2 rounded-xl border font-semibold">
                Reviews
            </a>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
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
@endsection
