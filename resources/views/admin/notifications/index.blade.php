@extends('layouts.app')

@section('title', 'Admin Notifications')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold">Notification Center</h1>
            <p class="text-sm text-gray-600 mt-1">
                Unread: {{ $unreadOrdersCount + $unreadReviewsCount }} (Orders: {{ $unreadOrdersCount }}, Reviews: {{ $unreadReviewsCount }})
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
            <form method="POST" action="{{ route('admin.notifications.read-all') }}">
                @csrf
                <input type="hidden" name="type" value="all">
                <button class="btn-primary w-full sm:w-auto">
                    Mark All Read
                </button>
            </form>
            <a href="{{ route('admin.dashboard') }}" class="btn-outline text-center">
                Back to Dashboard
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <div class="glass-card p-5">
            <div class="flex items-center justify-between gap-3 mb-4">
                <h2 class="text-lg font-semibold">Unread Orders</h2>
                <form method="POST" action="{{ route('admin.notifications.read-all') }}">
                    @csrf
                    <input type="hidden" name="type" value="order">
                    <button class="action-link">Read all orders</button>
                </form>
            </div>
            @forelse($unreadOrders as $order)
                <div class="border-b py-3 flex items-start justify-between gap-3">
                    <div>
                        <div class="font-semibold">{{ $order->name }} - {{ $order->phone }}</div>
                        <div class="text-sm text-gray-600">
                            {{ $order->product?->name ?? 'N/A' }} | Qty: {{ $order->quantity }} | {{ ucfirst($order->status) }}
                        </div>
                        <div class="text-xs text-gray-500 mt-1">{{ $order->created_at?->diffForHumans() }}</div>
                    </div>
                    <form method="POST" action="{{ route('admin.notifications.read', ['type' => 'order', 'id' => $order->id]) }}">
                        @csrf
                        <button class="action-link">Read</button>
                    </form>
                </div>
            @empty
                <div class="text-gray-500">No unread order notifications.</div>
            @endforelse
        </div>

        <div class="glass-card p-5">
            <div class="flex items-center justify-between gap-3 mb-4">
                <h2 class="text-lg font-semibold">Unread Reviews</h2>
                <form method="POST" action="{{ route('admin.notifications.read-all') }}">
                    @csrf
                    <input type="hidden" name="type" value="review">
                    <button class="action-link">Read all reviews</button>
                </form>
            </div>
            @forelse($unreadReviews as $review)
                <div class="border-b py-3 flex items-start justify-between gap-3">
                    <div>
                        <div class="font-semibold">{{ $review->name }} ({{ $review->rating }}/5)</div>
                        <div class="text-sm text-gray-600">{{ $review->product?->name ?? 'N/A' }}</div>
                        @if($review->comment)
                            <div class="text-sm text-gray-700 mt-1 line-clamp-2">{{ $review->comment }}</div>
                        @endif
                        <div class="text-xs text-gray-500 mt-1">{{ $review->created_at?->diffForHumans() }}</div>
                    </div>
                    <form method="POST" action="{{ route('admin.notifications.read', ['type' => 'review', 'id' => $review->id]) }}">
                        @csrf
                        <button class="action-link">Read</button>
                    </form>
                </div>
            @empty
                <div class="text-gray-500">No unread review notifications.</div>
            @endforelse
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 xl:grid-cols-2 gap-6">
        <div class="glass-card p-5">
            <h2 class="text-lg font-semibold mb-4">Recent Orders</h2>
            @forelse($recentOrders as $order)
                <div class="border-b py-3">
                    <div class="font-semibold">{{ $order->name }}</div>
                    <div class="text-sm text-gray-600">{{ $order->product?->name ?? 'N/A' }} | {{ ucfirst($order->status) }}</div>
                </div>
            @empty
                <div class="text-gray-500">No order notifications yet.</div>
            @endforelse
        </div>
        <div class="glass-card p-5">
            <h2 class="text-lg font-semibold mb-4">Recent Reviews</h2>
            @forelse($recentReviews as $review)
                <div class="border-b py-3">
                    <div class="font-semibold">{{ $review->name }} ({{ $review->rating }}/5)</div>
                    <div class="text-sm text-gray-600">{{ $review->product?->name ?? 'N/A' }}</div>
                </div>
            @empty
                <div class="text-gray-500">No review notifications yet.</div>
            @endforelse
        </div>
    </div>
@endsection
