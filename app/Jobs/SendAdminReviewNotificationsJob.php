<?php

namespace App\Jobs;

use App\Services\AdminPushNotifier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendAdminReviewNotificationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $productId,
        public string $productName,
        public string $name,
        public int $rating,
        public ?string $comment = null,
    ) {
    }

    public function handle(): void
    {
        AdminPushNotifier::send('New review submitted', [
            'Product: ' . $this->productName . ' (ID: ' . $this->productId . ')',
            'Name: ' . $this->name,
            'Rating: ' . $this->rating . ' / 5',
        ]);

        $adminEmail = (string) config('admin_notifications.email', '');
        if ($adminEmail === '') {
            return;
        }

        $body = "Product: {$this->productName} (ID: {$this->productId})\n"
            . "Name: {$this->name}\n"
            . "Rating: {$this->rating}\n"
            . "Comment: " . ($this->comment ?? '-') . "\n"
            . "Admin: " . route('admin.reviews.index');

        try {
            Mail::raw($body, function ($message) use ($adminEmail) {
                $message->to($adminEmail)->subject('New review submitted');
            });
        } catch (\Throwable $e) {
            // Ignore notification errors.
        }
    }
}
