<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AdminPushNotifier
{
    public static function send(string $title, array $lines = []): void
    {
        $text = $title;
        if (! empty($lines)) {
            $text .= "\n" . implode("\n", $lines);
        }

        self::sendTelegram($text);
        self::sendWebhook($title, $text, $lines);
    }

    private static function sendTelegram(string $text): void
    {
        $token = (string) config('admin_notifications.telegram_bot_token');
        $chatId = (string) config('admin_notifications.telegram_chat_id');

        if ($token === '' || $chatId === '') {
            return;
        }

        try {
            Http::timeout(5)->asForm()->post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $text,
                'disable_web_page_preview' => true,
            ]);
        } catch (\Throwable $e) {
            // Ignore push notification errors.
        }
    }

    private static function sendWebhook(string $title, string $text, array $lines): void
    {
        $url = (string) config('admin_notifications.webhook_url');
        if ($url === '') {
            return;
        }

        try {
            Http::timeout(5)->post($url, [
                'title' => $title,
                'text' => $text,
                'lines' => $lines,
            ]);
        } catch (\Throwable $e) {
            // Ignore push notification errors.
        }
    }
}
