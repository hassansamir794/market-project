<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class PingMonitor extends Command
{
    protected $signature = 'monitor:ping';
    protected $description = 'Ping an external monitoring URL.';

    public function handle(): int
    {
        $url = env('MONITORING_PING_URL');
        if (! $url) {
            $this->warn('MONITORING_PING_URL not set.');
            return self::SUCCESS;
        }

        try {
            Http::timeout(5)->get($url);
            $this->info('Ping sent.');
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Ping failed: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
