<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn-outline text-sm normal-case tracking-normal disabled:opacity-25']) }}>
    {{ $slot }}
</button>
