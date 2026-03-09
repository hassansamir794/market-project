@extends('layouts.app')

@section('title', 'Admin Order Requests')

@section('content')
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold">Order Requests</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn-outline">
            Back to Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <form id="orders-bulk-form" method="POST" action="{{ route('admin.order-requests.bulk') }}" class="mb-4 flex flex-col sm:flex-row gap-3 sm:items-center">
        @csrf
        <select name="action" class="select-clean w-full sm:w-auto">
            <option value="contacted">Mark contacted</option>
            <option value="completed">Mark completed</option>
            <option value="canceled">Mark canceled</option>
            <option value="delete">Delete selected</option>
        </select>
        <button class="btn-primary text-sm w-full sm:w-auto">Apply</button>
    </form>

    <div class="space-y-3 md:hidden">
        @forelse($orders as $order)
            <div class="glass-card p-4">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="meta-label">Product</div>
                        <div class="font-semibold text-gray-900">{{ $order->product?->name ?? 'N/A' }}</div>
                    </div>
                    <input form="orders-bulk-form" class="order-select mt-1 h-4 w-4" type="checkbox" name="ids[]" value="{{ $order->id }}">
                </div>

                <div class="mt-3 grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <div class="meta-label">Name</div>
                        <div class="font-semibold text-gray-800">{{ $order->name }}</div>
                    </div>
                    <div>
                        <div class="meta-label">Phone</div>
                        <div class="font-semibold text-gray-800">{{ $order->phone }}</div>
                    </div>
                    <div>
                        <div class="meta-label">Quantity</div>
                        <div class="font-semibold text-gray-800">{{ $order->quantity }}</div>
                    </div>
                    <div>
                        <div class="meta-label">Note</div>
                        <div class="text-gray-700">{{ $order->note ?? '-' }}</div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.order-requests.update', $order) }}" class="mt-4 flex gap-2">
                    @csrf
                    @method('PUT')
                    <select name="status" class="select-clean text-sm flex-1 min-w-0">
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                    <button class="action-link px-2">Save</button>
                </form>

                <div class="mt-4 flex items-center gap-4">
                    @if(!empty(env('ADMIN_WHATSAPP_NUMBER')))
                        @php
                            $waMessage = urlencode("Order request\n"
                                . "Product: " . ($order->product?->name ?? 'N/A') . "\n"
                                . "Name: {$order->name}\n"
                                . "Phone: {$order->phone}\n"
                                . "Quantity: {$order->quantity}\n"
                                . "Note: " . ($order->note ?? '-')
                            );
                        @endphp
                        <a target="_blank"
                           class="inline-flex items-center text-sm text-green-700 underline decoration-green-300 decoration-2 underline-offset-4"
                           href="https://wa.me/{{ env('ADMIN_WHATSAPP_NUMBER') }}?text={{ $waMessage }}">
                            Send WhatsApp
                        </a>
                    @endif

                    <form method="POST" action="{{ route('admin.order-requests.destroy', $order) }}" onsubmit="return confirm('Delete this request?')">
                        @csrf
                        @method('DELETE')
                        <button class="action-link-danger text-sm">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="glass-card p-6 text-center text-gray-500">No order requests yet.</div>
        @endforelse
    </div>

    <div class="admin-table-wrap">
        <div class="overflow-x-auto">
            <table class="admin-table min-w-[920px]">
                <thead>
                <tr>
                    <th class="p-4 text-left">
                        <input type="checkbox" onclick="document.querySelectorAll('.order-select').forEach(cb => cb.checked = this.checked)">
                    </th>
                    <th class="p-4 text-left">Product</th>
                    <th class="p-4 text-left">Name</th>
                    <th class="p-4 text-left">Phone</th>
                    <th class="p-4 text-left">Qty</th>
                    <th class="p-4 text-left">Note</th>
                    <th class="p-4 text-left">Status</th>
                    <th class="p-4 text-left">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td class="p-4">
                            <input form="orders-bulk-form" class="order-select" type="checkbox" name="ids[]" value="{{ $order->id }}">
                        </td>
                        <td class="p-4 font-semibold">{{ $order->product?->name ?? 'N/A' }}</td>
                        <td class="p-4">{{ $order->name }}</td>
                        <td class="p-4">{{ $order->phone }}</td>
                        <td class="p-4">{{ $order->quantity }}</td>
                        <td class="p-4 text-sm text-gray-700">{{ $order->note ?? '-' }}</td>
                        <td class="p-4">
                            <form method="POST" action="{{ route('admin.order-requests.update', $order) }}">
                                @csrf
                                @method('PUT')
                                <select name="status" class="select-clean w-auto text-sm">
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                                <button class="ml-2 action-link">Save</button>
                            </form>
                            @if(!empty(env('ADMIN_WHATSAPP_NUMBER')))
                                @php
                                    $waMessage = urlencode("Order request\n"
                                        . "Product: " . ($order->product?->name ?? 'N/A') . "\n"
                                        . "Name: {$order->name}\n"
                                        . "Phone: {$order->phone}\n"
                                        . "Quantity: {$order->quantity}\n"
                                        . "Note: " . ($order->note ?? '-')
                                    );
                                @endphp
                                <a target="_blank"
                                   class="inline-flex items-center mt-2 text-sm text-green-700 underline decoration-green-300 decoration-2 underline-offset-4"
                                   href="https://wa.me/{{ env('ADMIN_WHATSAPP_NUMBER') }}?text={{ $waMessage }}">
                                    Send WhatsApp
                                </a>
                            @endif
                        </td>
                        <td class="p-4">
                            <form method="POST" action="{{ route('admin.order-requests.destroy', $order) }}" onsubmit="return confirm('Delete this request?')">
                                @csrf
                                @method('DELETE')
                                <button class="action-link-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-6 text-center text-gray-500">No order requests yet.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $orders->links() }}
    </div>
@endsection
