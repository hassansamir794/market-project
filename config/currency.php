<?php

return [
    'code' => env('APP_CURRENCY', 'IQD'),
    'symbol' => env('APP_CURRENCY_SYMBOL', 'IQD'),
    'decimals' => (int) env('APP_CURRENCY_DECIMALS', 0),
    // Conversion rate from stored base price to display currency.
    // Example: if prices are stored in USD and you want IQD, set 1 USD -> 1300 IQD.
    'rate' => (float) env('APP_CURRENCY_RATE', 1),
];
