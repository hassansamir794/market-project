<?php

namespace App\Jobs;

use App\Services\AdminPushNotifier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendAdminOrderRequestNotificationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $productId,
        public string $productName,
        public string $name,
        public string $phone,
        public int $quantity,
        public ?string $note = null,
    ) {
    }

    public function handle(): void
    {
        AdminPushNotifier::send('New order request', [
            'Product: ' . $this->productName . ' (ID: ' . $this->productId . ')',
            'Name: ' . $this->name,
            'Phone: ' . $this->phone,
            'Quantity: ' . $this->quantity,
        ]);

        $adminEmail = (string) config('admin_notifications.email', '');
        if ($adminEmail === '') {
            return;
        }

        $adminWhatsapp = (string) config('admin_notifications.whatsapp_number', '');
        $waLink = null;
        if ($adminWhatsapp !== '') {
            $waMessage = urlencode(
                "New order request\n"
                . "Product: {$this->productName} (ID: {$this->productId})\n"
                . "Name: {$this->name}\n"
                . "Phone: {$this->phone}\n"
                . "Quantity: {$this->quantity}\n"
                . "Note: " . ($this->note ?? '-')
            );
            $waLink = "https://wa.me/{$adminWhatsapp}?text={$waMessage}";
        }

        $body = "Product: {$this->productName} (ID: {$this->productId})\n"
            . "Name: {$this->name}\n"
            . "Phone: {$this->phone}\n"
            . "Quantity: {$this->quantity}\n"
            . "Note: " . ($this->note ?? '-') . "\n"
            . "Admin: " . route('admin.order-requests.index')
            . ($waLink ? "\nWhatsApp: {$waLink}" : '');

        try {
            Mail::raw($body, function ($message) use ($adminEmail) {
                $message->to($adminEmail)->subject('New order request');
            });
        } catch (\Throwable $e) {
            // Ignore notification errors.
        }
    }
}
