<?php

namespace App\Console\Commands;

use App\Jobs\SendInventoryDigestJob;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class InventoryLowStockAlert extends Command
{
    protected $signature = 'inventory:alert-low-stock {--threshold=}';
    protected $description = 'Send low-stock alerts to admins.';

    public function handle(): int
    {
        $threshold = $this->threshold();
        $items = Product::query()
            ->where('stock', '<=', $threshold)
            ->orderBy('stock')
            ->orderBy('name')
            ->get(['id', 'name', 'stock']);

        if ($items->isEmpty()) {
            $this->info('No low-stock products.');
            Cache::forget('inventory.low_stock.last_hash');
            return self::SUCCESS;
        }

        $hash = sha1($items->map(fn ($p) => $p->id . ':' . $p->stock)->implode('|'));
        $lastHash = (string) Cache::get('inventory.low_stock.last_hash', '');
        if ($hash === $lastHash) {
            $this->info('Low-stock snapshot unchanged. Alert skipped.');
            return self::SUCCESS;
        }

        $lines = [
            'Threshold: <= ' . $threshold,
            'Low-stock products: ' . $items->count(),
        ];

        foreach ($items->take(30) as $item) {
            $lines[] = "- #{$item->id} {$item->name} (stock: {$item->stock})";
        }

        if ($items->count() > 30) {
            $lines[] = '... and ' . ($items->count() - 30) . ' more';
        }

        SendInventoryDigestJob::dispatch('Low stock alert', $lines);
        Cache::put('inventory.low_stock.last_hash', $hash, now()->addDays(7));

        $this->info('Low-stock alert queued.');

        return self::SUCCESS;
    }

    private function threshold(): int
    {
        $option = $this->option('threshold');
        if (is_numeric($option)) {
            return max(0, (int) $option);
        }

        return max(0, (int) config('inventory.low_stock_threshold', 5));
    }
}
