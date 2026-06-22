<?php

namespace App\Console\Commands;

use App\Services\SyncUserService;
use Illuminate\Console\Command;

class SyncPegawai extends Command
{
    protected $signature = 'app:sync-pegawai';
    protected $description = 'Sync semua data pegawai dari API eksternal';

    public function handle(): int
    {
        $this->info('Memulai sync data pegawai...');

        $result = app(SyncUserService::class)->syncAll();

        $this->info($result['message']);

        return $result['errors'] > 0 ? self::FAILURE : self::SUCCESS;
    }
}
