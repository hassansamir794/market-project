@extends('layouts.app')

@section('title', 'Admin Reviews')

@section('content')
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold">Reviews</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn-outline">
            Back to Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <form id="reviews-bulk-form" method="POST" action="{{ route('admin.reviews.bulk') }}" class="mb-4 flex flex-col sm:flex-row gap-3 sm:items-center">
        @csrf
        <select name="action" class="select-clean w-full sm:w-auto">
            <option value="approve">Approve selected</option>
            <option value="hide">Hide selected</option>
            <option value="delete">Delete selected</option>
        </select>
        <button class="btn-primary text-sm w-full sm:w-auto">Apply</button>
    </form>

    <div class="space-y-3 md:hidden">
        @forelse($reviews as $review)
            <div class="glass-card p-4">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="meta-label">Product</div>
                        <div class="font-semibold text-gray-900">{{ $review->product?->name ?? 'N/A' }}</div>
                    </div>
                    <input form="reviews-bulk-form" class="review-select mt-1 h-4 w-4" type="checkbox" name="ids[]" value="{{ $review->id }}">
                </div>

                <div class="mt-3 grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <div class="meta-label">Name</div>
                        <div class="font-semibold text-gray-800">{{ $review->name }}</div>
                    </div>
                    <div>
                        <div class="meta-label">Rating</div>
                        <div class="font-semibold text-gray-800">{{ $review->rating }} / 5</div>
                    </div>
                </div>

                <div class="mt-3">
                    <div class="meta-label">Comment</div>
                    <div class="text-sm text-gray-700 mt-1">{{ $review->comment ?? '-' }}</div>
                </div>

                <form method="POST" action="{{ route('admin.reviews.update', $review) }}" class="mt-4 flex gap-2">
                    @csrf
                    @method('PUT')
                    <select name="is_approved" class="select-clean text-sm flex-1 min-w-0">
                        <option value="1" @selected($review->is_approved)>Approved</option>
                        <option value="0" @selected(! $review->is_approved)>Hidden</option>
                    </select>
                    <button class="action-link px-2">Save</button>
                </form>

                <div class="mt-4">
                    <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('Delete this review?')">
                        @csrf
                        @method('DELETE')
                        <button class="action-link-danger text-sm">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="glass-card p-6 text-center text-gray-500">No reviews yet.</div>
        @endforelse
    </div>

    <div class="admin-table-wrap">
        <div class="overflow-x-auto">
            <table class="admin-table min-w-[820px]">
                <thead>
                <tr>
                    <th class="p-4 text-left">
                        <input type="checkbox" onclick="document.querySelectorAll('.review-select').forEach(cb => cb.checked = this.checked)">
                    </th>
                    <th class="p-4 text-left">Product</th>
                    <th class="p-4 text-left">Name</th>
                    <th class="p-4 text-left">Rating</th>
                    <th class="p-4 text-left">Comment</th>
                    <th class="p-4 text-left">Approved</th>
                    <th class="p-4 text-left">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($reviews as $review)
                    <tr>
                        <td class="p-4">
                            <input form="reviews-bulk-form" class="review-select" type="checkbox" name="ids[]" value="{{ $review->id }}">
                        </td>
                        <td class="p-4 font-semibold">{{ $review->product?->name ?? 'N/A' }}</td>
                        <td class="p-4">{{ $review->name }}</td>
                        <td class="p-4">{{ $review->rating }} / 5</td>
                        <td class="p-4 text-sm text-gray-700">{{ $review->comment ?? '-' }}</td>
                        <td class="p-4">
                            <form method="POST" action="{{ route('admin.reviews.update', $review) }}">
                                @csrf
                                @method('PUT')
                                <select name="is_approved" class="select-clean w-auto text-sm">
                                    <option value="1" @selected($review->is_approved)>Approved</option>
                                    <option value="0" @selected(! $review->is_approved)>Hidden</option>
                                </select>
                                <button class="ml-2 action-link">Save</button>
                            </form>
                        </td>
                        <td class="p-4">
                            <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('Delete this review?')">
                                @csrf
                                @method('DELETE')
                                <button class="action-link-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-6 text-center text-gray-500">No reviews yet.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $reviews->links() }}
    </div>
@endsection
