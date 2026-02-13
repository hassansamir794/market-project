@extends('layouts.app')

@section('title', 'Admin Products')

@section('content')
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold">Products</h1>

        <div class="flex gap-3">
            <a href="{{ route('admin.categories.index') }}"
               class="px-4 py-2 rounded-xl border font-semibold hover:bg-gray-50 transition">
                Manage Categories
            </a>

            <a href="{{ route('admin.products.create') }}"
               class="px-4 py-2 rounded-xl bg-black text-white font-semibold hover:opacity-90 transition">
                + Add Product
            </a>
        </div>
    </div>

    <div class="bg-white border rounded-3xl shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
            <tr>
                <th class="p-4 text-left">Product</th>
                <th class="p-4 text-left">Categories</th>
                <th class="p-4 text-left">Price</th>
                <th class="p-4 text-left">Actions</th>
            </tr>
            </thead>

            <tbody>
            @forelse($products as $product)
                <tr class="border-t">
                    <td class="p-4 font-semibold">
                        {{ $product->name }}
                    </td>

                    <td class="p-4">
                        @if($product->categories->count())
                            <div class="flex flex-wrap gap-2">
                                @foreach($product->categories as $cat)
                                    <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-sm font-semibold text-gray-700">
                                        {{ $cat->name }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-sm text-gray-400">No category</span>
                        @endif
                    </td>

                    <td class="p-4 font-semibold">
                        ${{ number_format($product->price, 2) }}
                    </td>

                    <td class="p-4">
                        <div class="flex gap-4">
                            <a href="{{ route('admin.products.edit', $product) }}"
                               class="font-semibold underline">
                                Edit
                            </a>

                            <form method="POST"
                                  action="{{ route('admin.products.destroy', $product) }}"
                                  onsubmit="return confirm('Delete this product?')">
                                @csrf
                                @method('DELETE')
                                <button class="font-semibold underline text-red-600">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-6 text-center text-gray-500">
                        No products yet.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $products->links() }}
    </div>
@endsection
