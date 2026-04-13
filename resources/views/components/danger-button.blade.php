<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-[0_12px_22px_rgba(190,24,93,0.22)] transition hover:bg-rose-500']) }}>
    {{ $slot }}
</button>
