@extends('layouts.app')

@section('title', 'Admin Reviews')

@section('content')
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold">Reviews</h1>
        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl border font-semibold">
            Back to Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <form id="reviews-bulk-form" method="POST" action="{{ route('admin.reviews.bulk') }}" class="mb-4 flex flex-wrap gap-3 items-center">
        @csrf
        <select name="action" class="border rounded-lg px-3 py-2 text-sm">
            <option value="approve">Approve selected</option>
            <option value="hide">Hide selected</option>
            <option value="delete">Delete selected</option>
        </select>
        <button class="px-4 py-2 rounded-xl bg-black text-white text-sm font-semibold">Apply</button>
    </form>

    <div class="bg-white border rounded-3xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[820px]">
                <thead class="bg-gray-50">
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
                    <tr class="border-t">
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
                                <select name="is_approved" class="border rounded-lg px-2 py-1 text-sm">
                                    <option value="1" @selected($review->is_approved)>Approved</option>
                                    <option value="0" @selected(! $review->is_approved)>Hidden</option>
                                </select>
                                <button class="ml-2 text-sm font-semibold underline">Save</button>
                            </form>
                        </td>
                        <td class="p-4">
                            <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('Delete this review?')">
                                @csrf
                                @method('DELETE')
                                <button class="font-semibold underline text-red-600">Delete</button>
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
