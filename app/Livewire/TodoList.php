<?php

namespace App\Livewire;

use App\Models\Task;
use Livewire\Component;
use Livewire\Attributes\Rule;

class TodoList extends Component
{
    #[Rule('required|min:3|max:255')]
    public $title = '';

    public function addTask()
    {
        $this->validate();

        Task::create([
            'title' => $this->title,
            'is_completed' => false,
            'sync_status' => 'pending',
        ]);

        $this->title = '';
        $this->queueSync();
    }

    public function toggleTask($id)
    {
        $task = Task::find($id);
        $task->update([
            'is_completed' => !$task->is_completed,
            'sync_status' => 'pending',
        ]);
        $this->queueSync();
    }

    public function deleteTask($id)
    {
        Task::destroy($id);
        $this->queueSync();
    }

    public function clearCompleted()
    {
        Task::where('is_completed', true)->delete();
        $this->queueSync();
    }

    private function queueSync(): void
    {
        // Trigger sync in background
        // For NativePHP, this could be a scheduled command or event
        // For now, we can dispatch to the queue or just log
        // Example: SyncTask::dispatch();
    }

    public function render()
    {
        return view('livewire.todo-list', [
            'tasks' => Task::latest()->get(),
        ]);
    }
}