<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup';
    protected $description = 'Create a database backup using mysqldump.';

    public function handle(): int
    {
        $connection = config('database.default');
        $config = config("database.connections.{$connection}");

        if ($connection !== 'mysql') {
            $this->error('Backup supports only MySQL.');
            return self::FAILURE;
        }

        $dir = env('BACKUP_DIR', 'backups');
        $filename = 'db_' . now()->format('Ymd_His') . '.sql';
        $path = storage_path('app/' . trim($dir, '/'));

        if (! is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $filePath = $path . '/' . $filename;
        $user = $config['username'];
        $pass = $config['password'];
        $host = $config['host'];
        $db = $config['database'];

        $passPart = $pass !== '' ? '-p' . escapeshellarg($pass) : '';
        $command = "mysqldump -h " . escapeshellarg($host) . " -u " . escapeshellarg($user) . " {$passPart} " . escapeshellarg($db) . " > " . escapeshellarg($filePath);

        $result = null;
        @exec($command, $output, $result);

        if ($result !== 0 || ! file_exists($filePath)) {
            $this->error('Backup failed. Check mysqldump availability.');
            return self::FAILURE;
        }

        $this->info("Backup created: {$filePath}");
        return self::SUCCESS;
    }
}
