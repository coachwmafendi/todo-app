<?php

namespace Tests\Feature\Api;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SyncControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_push_creates_or_updates_tasks()
    {
        $response = $this->postJson('/api/sync', [
            'tasks' => [
                [
                    'local_id' => 'a1b2c3d4-1111-2222-3333-444444444444',
                    'title' => 'Test task',
                    'is_completed' => false,
                    'updated_at' => now()->toIso8601String(),
                ],
            ],
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('tasks', ['local_id' => 'a1b2c3d4-1111-2222-3333-444444444444', 'title' => 'Test task']);
    }

    public function test_pull_returns_tasks()
    {
        Task::create([
            'local_id' => 'a1b2c3d4-1111-2222-3333-444444444444',
            'title' => 'Test task',
            'is_completed' => false,
        ]);

        $response = $this->getJson('/api/sync');

        $response->assertOk()
            ->assertJsonCount(1, 'tasks');
    }

    public function test_pull_with_since_parameter()
    {
        Task::create([
            'local_id' => 'a1b2c3d4-1111-2222-3333-444444444444',
            'title' => 'Old task',
            'is_completed' => false,
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        Task::create([
            'local_id' => 'b2c3d4e5-2222-3333-4444-555555555555',
            'title' => 'New task',
            'is_completed' => false,
        ]);

        $response = $this->getJson('/api/sync?since=' . now()->subHour());

        $response->assertOk();
    }
}