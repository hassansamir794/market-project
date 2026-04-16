<?php

return [
    'honeypot_field' => env('HONEYPOT_FIELD', 'company'),
    'honeypot_timestamp_field' => env('HONEYPOT_TIMESTAMP_FIELD', 'form_started_at'),
    'minimum_form_fill_seconds' => (int) env('MINIMUM_FORM_FILL_SECONDS', 2),
];
