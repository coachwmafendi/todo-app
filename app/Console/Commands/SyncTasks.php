<?php

namespace App\Console\Commands;

use App\Services\SyncService;
use Illuminate\Console\Command;

class SyncTasks extends Command
{
    protected $signature = 'tasks:sync';
    protected $description = 'Push and pull task sync changes';

    public function handle(): void
    {
        $baseUrl = config('app.url');
        $apiToken = config('app.api_token', 'default-token');

        $syncService = new SyncService($baseUrl, $apiToken);

        $this->info('Starting sync...');
        $syncService->pushPending();
        $syncService->pullChanges();
        $this->info('Sync complete.');
    }
}