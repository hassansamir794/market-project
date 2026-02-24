@props([
    'amount' => 0,
    'symbol' => config('currency.symbol', 'IQD'),
    'decimals' => (int) config('currency.decimals', 0),
    'rate' => (float) config('currency.rate', 1),
    'convert' => true,
])

@php
    $numericAmount = is_numeric($amount) ? (float) $amount : 0;
    $value = $convert ? $numericAmount * $rate : $numericAmount;
@endphp

<span>{{ $symbol }} {{ number_format($value, $decimals) }}</span>
