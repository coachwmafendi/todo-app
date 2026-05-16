<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use Illuminate\Http\Request;

class SyncController
{
    public function push(Request $request)
    {
        $validated = $request->validate([
            'tasks' => 'required|array',
            'tasks.*.local_id' => 'required|string',
            'tasks.*.title' => 'required|string|min:3|max:255',
            'tasks.*.is_completed' => 'boolean',
            'tasks.*.updated_at' => 'required|date',
        ]);

        $synced = [];

        foreach ($validated['tasks'] as $taskData) {
            $task = Task::updateOrCreate(
                ['local_id' => $taskData['local_id']],
                [
                    'title' => $taskData['title'],
                    'is_completed' => $taskData['is_completed'] ?? false,
                    'updated_at' => $taskData['updated_at'],
                ]
            );

            $synced[] = [
                'local_id' => $task->local_id,
                'server_id' => $task->id,
                'status' => 'ok',
            ];
        }

        return response()->json(['synced' => $synced]);
    }

    public function pull(Request $request)
    {
        $since = $request->query('since');

        $query = Task::query();

        if ($since) {
            $query->where('updated_at', '>', $since);
        }

        return response()->json([
            'tasks' => $query->get()->map(fn($t) => [
                'id' => $t->id,
                'local_id' => $t->local_id,
                'title' => $t->title,
                'is_completed' => $t->is_completed,
                'updated_at' => $t->updated_at->toIso8601String(),
                'photo_path' => $t->photo_path,
                'location_lat' => $t->location_lat,
                'location_lng' => $t->location_lng,
            ]),
        ]);
    }
}