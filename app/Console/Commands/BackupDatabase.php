<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BackupDatabase extends Command
{
    protected $signature = 'backup:db';
    protected $description = 'Backup database ke storage dan catat penggunaan disk';

    public function handle()
    {
        $conn = config('database.default');
        $cfg = config("database.connections.$conn");
        $now = now();
        $stamp = $now->format('Ymd_His');
        $dir = 'backups/' . $now->format('Y/m/d');
        Storage::disk('local')->makeDirectory($dir);

        $backupPath = storage_path('app/' . $dir);
        $driver = $cfg['driver'] ?? 'mysql';
        $dbName = $cfg['database'] ?? '';
        $ok = false; $method = null; $files = [];

        // Try mysqldump if available
        if ($driver === 'mysql') {
            $mysqldump = env('MYSQLDUMP_PATH', 'mysqldump');
            $host = $cfg['host'] ?? '127.0.0.1';
            $port = $cfg['port'] ?? 3306;
            $user = $cfg['username'] ?? '';
            $pass = $cfg['password'] ?? '';
            $cmd = sprintf('"%s" --host=%s --port=%s -u%s %s --single-transaction --quick --lock-tables=false %s',
                $mysqldump, $host, $port, $user, $pass ? "-p$pass" : '', $dbName);
            try {
                $output = shell_exec($cmd);
                if ($output && strlen($output) > 0) {
                    $sqlFile = "$backupPath/db_{$stamp}.sql";
                    file_put_contents($sqlFile, $output);
                    $files[] = $sqlFile;
                    $ok = true; $method = 'mysqldump';
                }
            } catch (\Throwable $e) {
                // fallthrough
            }
        }

        // Fallback: export tiap tabel ke JSONL
        if (!$ok) {
            try {
                $tables = [];
                if ($driver === 'mysql') {
                    $list = DB::select('SHOW TABLES');
                    $key = 'Tables_in_' . $dbName;
                    foreach ($list as $row) { $tables[] = $row->$key; }
                } else {
                    $tables = [];
                }
                foreach ($tables as $table) {
                    $path = "$backupPath/{$table}_{$stamp}.jsonl";
                    $fh = fopen($path, 'w');
                    DB::table($table)->orderByRaw('1')->chunk(1000, function($rows) use ($fh) {
                        foreach ($rows as $r) { fwrite($fh, json_encode($r) . "\n"); }
                    });
                    fclose($fh);
                    $files[] = $path;
                }
                $ok = count($files) > 0; $method = 'jsonl';
            } catch (\Throwable $e) {
                $this->error('Backup fallback gagal: ' . $e->getMessage());
            }
        }

        // Disk usage logging
        $root = storage_path('app');
        $storageUsed = $this->dirSize($root);
        $free = @disk_free_space($root);
        $total = @disk_total_space($root);

        Log::info('backup', [
            'ok' => $ok,
            'method' => $method,
            'db' => $dbName,
            'driver' => $driver,
            'backup_dir' => $backupPath,
            'files' => $files,
            'storage_used_bytes' => $storageUsed,
            'storage_free_bytes' => $free,
            'storage_total_bytes' => $total,
            'time' => $now->toIso8601String(),
        ]);

        $this->info(($ok ? 'SUKSES' : 'GAGAL') . " backup (method: $method) ke $backupPath");
        return $ok ? 0 : 1;
    }

    private function dirSize($dir)
    {
        $size = 0;
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS)) as $file) {
            $size += $file->getSize();
        }
        return $size;
    }
}