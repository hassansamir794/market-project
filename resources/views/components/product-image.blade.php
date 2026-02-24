@props([
    'path' => null,
    'alt' => '',
    'class' => '',
])

@php
    $pathValue = $path ?: '';
    $thumbPath = $pathValue ? str_replace('products/', 'products/thumbs/', $pathValue) : null;
    $thumbExists = $thumbPath ? \Illuminate\Support\Facades\Storage::disk('public')->exists($thumbPath) : false;
@endphp

@if($pathValue)
    <div class="h-full w-full bg-gray-100" @if($thumbExists) style="background-image:url('{{ asset('storage/'.$thumbPath) }}'); background-size:cover; background-position:center;" @endif>
        <img
            src="{{ $thumbExists ? asset('storage/'.$thumbPath) : asset('storage/'.$pathValue) }}"
            @if($thumbExists) data-full="{{ asset('storage/'.$pathValue) }}" @endif
            alt="{{ $alt }}"
            loading="lazy"
            class="{{ $thumbExists ? 'blur-sm transition' : '' }} {{ $class }}"
        >
    </div>
@else
    <div class="h-full w-full flex items-center justify-center text-gray-400">
        No Image
    </div>
@endif
