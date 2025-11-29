<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Models\Pengguna;

class GenerateApiTokens extends Command
{
    protected $signature = 'user:generate-api-tokens {--count=12} {--force}';
    protected $description = 'Generate api_token untuk pengguna aktif';

    public function handle()
    {
        $count = (int) $this->option('count');
        $force = (bool) $this->option('force');

        $query = Pengguna::query()->where('status', 1);
        if (!$force) {
            $query->whereNull('api_token');
        }

        $users = $query->orderBy('id_pengguna', 'asc')->limit($count)->get();

        if ($users->isEmpty()) {
            $this->info('Tidak ada pengguna yang perlu dibuat token.');
            return 0;
        }

        $this->info('Membuat token untuk ' . $users->count() . ' pengguna...');
        foreach ($users as $u) {
            $token = Str::random(64);
            while (Pengguna::where('api_token', $token)->exists()) {
                $token = Str::random(64);
            }
            $u->api_token = $token;
            $u->save();

            $this->line(sprintf('ID: %d | Username: %s | Token: %s', $u->id_pengguna, $u->username, $u->api_token));
        }

        $this->info('Selesai.');
        return 0;
    }
}

