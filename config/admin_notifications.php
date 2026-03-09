<?php

return [
    'email' => env('ADMIN_NOTIFY_EMAIL', ''),
    'whatsapp_number' => env('ADMIN_WHATSAPP_NUMBER', ''),
    'webhook_url' => env('ADMIN_NOTIFICATION_WEBHOOK_URL', ''),

    'telegram_bot_token' => env('ADMIN_TELEGRAM_BOT_TOKEN', ''),
    'telegram_chat_id'   => env('ADMIN_TELEGRAM_CHAT_ID', ''),
];
