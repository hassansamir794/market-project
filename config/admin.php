<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Admin Registration
    |--------------------------------------------------------------------------
    |
    | Set ADMIN_REGISTRATION_CODE in .env to allow creating admin users from
    | the registration form. Leave empty to disable admin self-registration.
    |
    */
    'self_registration_enabled' => (bool) env('ADMIN_SELF_REGISTRATION_ENABLED', false),
    'registration_code' => env('ADMIN_REGISTRATION_CODE'),
];
