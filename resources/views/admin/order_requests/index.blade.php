@extends('layouts.app')

@section('title', 'Admin Order Requests')

@section('content')
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold">Order Requests</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn-outline">Back to Dashboard</a>
    </div>

    @if(session('success'))
        <div class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 mb-5">
        <div class="glass-card p-4">
            <div class="meta-label">All</div>
            <div class="text-2xl font-bold">{{ $summary['all'] ?? 0 }}</div>
        </div>
        <div class="glass-card p-4">
            <div class="meta-label">New</div>
            <div class="text-2xl font-bold">{{ $summary['new'] ?? 0 }}</div>
        </div>
        <div class="glass-card p-4">
            <div class="meta-label">Active</div>
            <div class="text-2xl font-bold">{{ $summary['active'] ?? 0 }}</div>
        </div>
        <div class="glass-card p-4">
            <div class="meta-label">Delivered</div>
            <div class="text-2xl font-bold">{{ $summary['delivered'] ?? 0 }}</div>
        </div>
        <div class="glass-card p-4 col-span-2 lg:col-span-1">
            <div class="meta-label">Canceled</div>
            <div class="text-2xl font-bold">{{ $summary['canceled'] ?? 0 }}</div>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.order-requests.index') }}" class="form-panel mb-5 grid grid-cols-1 md:grid-cols-[1fr_220px_auto] gap-3">
        <input name="q" value="{{ $q }}" class="input-clean" placeholder="Search by product, customer, phone, or notes">
        <select name="status" class="select-clean">
            <option value="">All statuses</option>
            @foreach($statuses as $value => $label)
                <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <div class="flex gap-3">
            <button class="btn-primary">Filter</button>
            @if($q !== '' || $status !== '')
                <a href="{{ route('admin.order-requests.index') }}" class="btn-outline">Reset</a>
            @endif
        </div>
    </form>

    <form id="orders-bulk-form" method="POST" action="{{ route('admin.order-requests.bulk') }}" class="mb-4 flex flex-col sm:flex-row gap-3 sm:items-center">
        @csrf
        <select name="action" class="select-clean w-full sm:w-auto">
            <option value="confirmed">Mark confirmed</option>
            <option value="preparing">Mark preparing</option>
            <option value="ready">Mark ready</option>
            <option value="delivered">Mark delivered</option>
            <option value="canceled">Mark canceled</option>
            <option value="delete">Delete selected</option>
        </select>
        <button class="btn-primary text-sm w-full sm:w-auto">Apply</button>
    </form>

    <div class="space-y-3 md:hidden">
        @forelse($orders as $order)
            @php
                $statusLabel = $statuses[$order->status] ?? ucfirst($order->status);
                $waMessage = rawurlencode("Hello {$order->name},\nWe are contacting you about your order for {$order->product?->name}.\nStatus: {$statusLabel}\nQuantity: {$order->quantity}\n\nPlease reply if you need any changes.");
            @endphp
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
                        <div class="meta-label">Updated</div>
                        <div class="text-gray-700">{{ optional($order->status_updated_at)->diffForHumans() ?? 'Not yet' }}</div>
                    </div>
                </div>

                <div class="mt-3">
                    <span class="chip">{{ $statusLabel }}</span>
                </div>

                @if($order->note)
                    <div class="mt-3">
                        <div class="meta-label">Customer Note</div>
                        <div class="text-sm text-gray-700">{{ $order->note }}</div>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.order-requests.update', $order) }}" class="mt-4 space-y-3">
                    @csrf
                    @method('PUT')
                    <select name="status" class="select-clean text-sm w-full">
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}" @selected($order->status === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <textarea name="admin_note" rows="3" class="textarea-clean" placeholder="Private admin note">{{ old('admin_note', $order->admin_note) }}</textarea>
                    <button class="btn-primary w-full">Save</button>
                </form>

                <div class="mt-4 flex items-center gap-4">
                    @if(!empty(config('admin_notifications.whatsapp_number')))
                        <a target="_blank" class="action-link" href="https://wa.me/{{ config('admin_notifications.whatsapp_number') }}?text={{ $waMessage }}">
                            Message customer
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
            <table class="admin-table min-w-[1180px]">
                <thead>
                    <tr>
                        <th class="p-4 text-left">
                            <input type="checkbox" onclick="document.querySelectorAll('.order-select').forEach(cb => cb.checked = this.checked)">
                        </th>
                        <th class="p-4 text-left">Product</th>
                        <th class="p-4 text-left">Customer</th>
                        <th class="p-4 text-left">Qty</th>
                        <th class="p-4 text-left">Customer Note</th>
                        <th class="p-4 text-left">Admin Note</th>
                        <th class="p-4 text-left">Status</th>
                        <th class="p-4 text-left">Updated</th>
                        <th class="p-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        @php
                            $statusLabel = $statuses[$order->status] ?? ucfirst($order->status);
                            $waMessage = rawurlencode("Hello {$order->name},\nWe are contacting you about your order for {$order->product?->name}.\nStatus: {$statusLabel}\nQuantity: {$order->quantity}\n\nPlease reply if you need any changes.");
                            $formId = 'order-update-' . $order->id;
                        @endphp
                        <tr>
                            <td class="p-4">
                                <input form="orders-bulk-form" class="order-select" type="checkbox" name="ids[]" value="{{ $order->id }}">
                            </td>
                            <td class="p-4 font-semibold">{{ $order->product?->name ?? 'N/A' }}</td>
                            <td class="p-4">
                                <div class="font-semibold">{{ $order->name }}</div>
                                <div class="text-sm text-gray-600">{{ $order->phone }}</div>
                            </td>
                            <td class="p-4 font-semibold">{{ $order->quantity }}</td>
                            <td class="p-4 text-sm text-gray-700">{{ $order->note ?? '—' }}</td>
                            <td class="p-4">
                                <textarea form="{{ $formId }}" name="admin_note" rows="3" class="textarea-clean min-w-[220px]" placeholder="Private admin note">{{ old('admin_note', $order->admin_note) }}</textarea>
                            </td>
                            <td class="p-4">
                                <select form="{{ $formId }}" name="status" class="select-clean w-[160px] text-sm">
                                    @foreach($statuses as $value => $label)
                                        <option value="{{ $value }}" @selected($order->status === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-4 text-sm text-gray-700">
                                {{ optional($order->status_updated_at)->format('Y-m-d H:i') ?? 'Not yet' }}
                            </td>
                            <td class="p-4">
                                <form id="{{ $formId }}" method="POST" action="{{ route('admin.order-requests.update', $order) }}">
                                    @csrf
                                    @method('PUT')
                                </form>
                                <div class="flex flex-col items-start gap-2">
                                    <button form="{{ $formId }}" class="action-link">Save</button>
                                    @if(!empty(config('admin_notifications.whatsapp_number')))
                                        <a target="_blank" class="action-link" href="https://wa.me/{{ config('admin_notifications.whatsapp_number') }}?text={{ $waMessage }}">
                                            Message customer
                                        </a>
                                    @endif
                                </div>
                                <form method="POST" action="{{ route('admin.order-requests.destroy', $order) }}" onsubmit="return confirm('Delete this request?')" class="mt-3">
                                    @csrf
                                    @method('DELETE')
                                    <button class="action-link-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-6 text-center text-gray-500">No order requests yet.</td>
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
