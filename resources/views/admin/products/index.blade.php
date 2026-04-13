@extends('layouts.app')

@section('title', 'Admin Products')

@section('content')
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold">Products</h1>

        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <a href="{{ route('admin.dashboard') }}"
               class="btn-outline text-center">
                Back to Dashboard
            </a>

            <a href="{{ route('admin.categories.index') }}"
               class="btn-outline text-center">
                Manage Categories
            </a>

            <a href="{{ route('admin.products.create') }}"
               class="btn-primary text-center">
                + Add Product
            </a>
        </div>
    </div>

    <div class="space-y-3 md:hidden">
        @forelse($products as $product)
            @php
                $isAvailable = (bool) ($product->is_available ?? true);
                $inStock = (int) ($product->stock ?? 0) > 0;
                $imageCount = count($product->gallery_images ?? []);
            @endphp
            <div class="glass-card p-4">
                <div class="flex items-start justify-between gap-3">
                    <div class="font-semibold text-gray-900">{{ $product->name }}</div>
                    <div class="price-tag px-3 py-1 text-sm">
                        <x-money :amount="$product->price" />
                    </div>
                </div>

                <div class="mt-3 grid grid-cols-3 gap-3 text-sm">
                    <div>
                        <div class="meta-label">Stock</div>
                        <div class="font-semibold text-gray-800">{{ $product->stock ?? 0 }}</div>
                    </div>
                    <div>
                        <div class="meta-label">Views</div>
                        <div class="font-semibold text-gray-800">{{ $product->views ?? 0 }}</div>
                    </div>
                    <div>
                        <div class="meta-label">Images</div>
                        <div class="font-semibold text-gray-800">{{ $imageCount }}</div>
                    </div>
                </div>

                <div class="mt-3">
                    <div class="meta-label mb-2">Categories</div>
                    @if($product->categories->count())
                        <div class="flex flex-wrap gap-2">
                            @foreach($product->categories as $cat)
                                <span class="chip">
                                    {{ $cat->name }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <span class="text-sm text-gray-400">No category</span>
                    @endif
                </div>

                <div class="mt-3">
                    @if($isAvailable && $inStock)
                        <span class="status-pill status-pill-success text-xs">
                            Available
                        </span>
                    @elseif(!$isAvailable)
                        <span class="chip text-xs">
                            Hidden
                        </span>
                    @else
                        <span class="status-pill status-pill-danger text-xs">
                            Out of stock
                        </span>
                    @endif
                </div>

                <div class="mt-4 flex items-center gap-4">
                    <a href="{{ route('admin.products.edit', $product) }}" class="action-link">
                        Edit
                    </a>
                    <form method="POST"
                          action="{{ route('admin.products.destroy', $product) }}"
                          onsubmit="return confirm('Delete this product?')">
                        @csrf
                        @method('DELETE')
                        <button class="action-link-danger">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="glass-card p-6 text-center text-gray-500">No products yet.</div>
        @endforelse
    </div>

    <div class="admin-table-wrap">
        <div class="overflow-x-auto">
        <table class="admin-table min-w-[720px]">
            <thead>
            <tr>
                <th class="p-4 text-left">Product</th>
                <th class="p-4 text-left">Categories</th>
                <th class="p-4 text-left">Price</th>
                <th class="p-4 text-left">Stock</th>
                <th class="p-4 text-left">Views</th>
                <th class="p-4 text-left">Images</th>
                <th class="p-4 text-left">Status</th>
                <th class="p-4 text-left">Actions</th>
            </tr>
            </thead>

            <tbody>
            @forelse($products as $product)
                @php
                    $isAvailable = (bool) ($product->is_available ?? true);
                    $inStock = (int) ($product->stock ?? 0) > 0;
                    $imageCount = count($product->gallery_images ?? []);
                @endphp
                <tr>
                    <td class="p-4 font-semibold">
                        {{ $product->name }}
                    </td>

                    <td class="p-4">
                        @if($product->categories->count())
                            <div class="flex flex-wrap gap-2">
                                @foreach($product->categories as $cat)
                                    <span class="chip">
                                        {{ $cat->name }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-sm text-gray-400">No category</span>
                        @endif
                    </td>

                    <td class="p-4 font-semibold">
                        <x-money :amount="$product->price" />
                    </td>

                    <td class="p-4 font-semibold">
                        {{ $product->stock ?? 0 }}
                    </td>

                    <td class="p-4 font-semibold">
                        {{ $product->views ?? 0 }}
                    </td>

                    <td class="p-4 font-semibold">
                        {{ $imageCount }}
                    </td>

                    <td class="p-4">
                        @if($isAvailable && $inStock)
                            <span class="status-pill status-pill-success text-sm">
                                Available
                            </span>
                        @elseif(!$isAvailable)
                            <span class="chip text-sm">
                                Hidden
                            </span>
                        @else
                            <span class="status-pill status-pill-danger text-sm">
                                Out of stock
                            </span>
                        @endif
                    </td>

                    <td class="p-4">
                        <div class="flex flex-wrap gap-4">
                            <a href="{{ route('admin.products.edit', $product) }}"
                               class="action-link">
                                Edit
                            </a>

                            <form method="POST"
                                  action="{{ route('admin.products.destroy', $product) }}"
                                  onsubmit="return confirm('Delete this product?')">
                                @csrf
                                @method('DELETE')
                                <button class="action-link-danger">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="p-6 text-center text-gray-500">
                        No products yet.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $products->links() }}
    </div>
@endsection
