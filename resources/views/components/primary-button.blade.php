<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-primary text-sm normal-case tracking-normal']) }}>
    {{ $slot }}
</button>
