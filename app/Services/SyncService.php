<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SyncService
{
    public function __construct(private string $baseUrl, private string $apiToken) {}

    public function pushPending(): void
    {
        $pending = Task::where('sync_status', 'pending')->get();

        if ($pending->isEmpty()) return;

        $response = Http::withToken($this->apiToken)
            ->post("{$this->baseUrl}/api/sync", [
                'tasks' => $pending->map(fn($t) => [
                    'local_id' => $t->local_id,
                    'title' => $t->title,
                    'is_completed' => $t->is_completed,
                    'updated_at' => $t->updated_at->toIso8601String(),
                    'photo_path' => $t->photo_path,
                    'location_lat' => $t->location_lat,
                    'location_lng' => $t->location_lng,
                ])->toArray(),
            ]);

        if ($response->successful()) {
            foreach ($response->json('synced', []) as $synced) {
                Task::where('local_id', $synced['local_id'])->update([
                    'remote_id' => $synced['server_id'],
                    'sync_status' => 'synced',
                    'last_synced_at' => now(),
                ]);
            }
        } else {
            $pending->each->markError();
        }
    }

    public function pullChanges(): void
    {
        $lastSync = Task::whereNotNull('last_synced_at')->max('last_synced_at');

        $response = Http::withToken($this->apiToken)
            ->get("{$this->baseUrl}/api/sync", ['since' => $lastSync]);

        if ($response->successful()) {
            foreach ($response->json('tasks', []) as $taskData) {
                Task::updateOrCreate(
                    ['remote_id' => $taskData['id']],
                    [
                        'local_id' => $taskData['local_id'] ?? (string) Str::uuid(),
                        'title' => $taskData['title'],
                        'is_completed' => $taskData['is_completed'],
                        'sync_status' => 'synced',
                        'last_synced_at' => now(),
                    ]
                );
            }
        }
    }
}