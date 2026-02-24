@extends('layouts.app')

@section('title', 'Admin Order Requests')

@section('content')
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold">Order Requests</h1>
        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl border font-semibold">
            Back to Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <form id="orders-bulk-form" method="POST" action="{{ route('admin.order-requests.bulk') }}" class="mb-4 flex flex-wrap gap-3 items-center">
        @csrf
        <select name="action" class="border rounded-lg px-3 py-2 text-sm">
            <option value="contacted">Mark contacted</option>
            <option value="completed">Mark completed</option>
            <option value="canceled">Mark canceled</option>
            <option value="delete">Delete selected</option>
        </select>
        <button class="px-4 py-2 rounded-xl bg-black text-white text-sm font-semibold">Apply</button>
    </form>

    <div class="bg-white border rounded-3xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[920px]">
                <thead class="bg-gray-50">
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
                    <tr class="border-t">
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
                                <select name="status" class="border rounded-lg px-2 py-1 text-sm">
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                                <button class="ml-2 text-sm font-semibold underline">Save</button>
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
                                   class="inline-flex items-center mt-2 text-sm text-green-700 underline"
                                   href="https://wa.me/{{ env('ADMIN_WHATSAPP_NUMBER') }}?text={{ $waMessage }}">
                                    Send WhatsApp
                                </a>
                            @endif
                        </td>
                        <td class="p-4">
                            <form method="POST" action="{{ route('admin.order-requests.destroy', $order) }}" onsubmit="return confirm('Delete this request?')">
                                @csrf
                                @method('DELETE')
                                <button class="font-semibold underline text-red-600">Delete</button>
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
