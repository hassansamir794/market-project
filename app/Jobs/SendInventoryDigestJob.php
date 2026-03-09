<?php

namespace App\Jobs;

use App\Services\AdminPushNotifier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendInventoryDigestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $subject,
        public array $lines = [],
    ) {
    }

    public function handle(): void
    {
        AdminPushNotifier::send($this->subject, $this->lines);

        $adminEmail = (string) config('admin_notifications.email', '');
        if ($adminEmail === '') {
            return;
        }

        $body = $this->subject;
        if (! empty($this->lines)) {
            $body .= "\n" . implode("\n", $this->lines);
        }

        try {
            Mail::raw($body, function ($message) use ($adminEmail) {
                $message->to($adminEmail)->subject($this->subject);
            });
        } catch (\Throwable $e) {
            // Ignore notification errors.
        }
    }
}
