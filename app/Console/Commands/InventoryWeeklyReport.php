<?php

namespace App\Console\Commands;

use App\Jobs\SendInventoryDigestJob;
use App\Models\Product;
use Illuminate\Console\Command;

class InventoryWeeklyReport extends Command
{
    protected $signature = 'inventory:weekly-report {--threshold=}';
    protected $description = 'Send weekly inventory report to admins.';

    public function handle(): int
    {
        $threshold = $this->threshold();

        $total = Product::count();
        $outOfStock = Product::where('stock', '<=', 0)->count();
        $lowStock = Product::where('stock', '>', 0)->where('stock', '<=', $threshold)->count();
        $totalViews = (int) Product::sum('views');

        $topViewed = Product::query()
            ->orderByDesc('views')
            ->orderBy('name')
            ->take(5)
            ->get(['id', 'name', 'views', 'stock']);

        $lines = [
            'Inventory weekly summary',
            'Total products: ' . $total,
            'Out of stock: ' . $outOfStock,
            'Low stock (1-' . $threshold . '): ' . $lowStock,
            'Total product views: ' . $totalViews,
            'Top viewed products:',
        ];

        foreach ($topViewed as $product) {
            $lines[] = "- #{$product->id} {$product->name} (views: {$product->views}, stock: {$product->stock})";
        }

        SendInventoryDigestJob::dispatch('Weekly inventory report', $lines);
        $this->info('Weekly inventory report queued.');

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
