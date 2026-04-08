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
        ]);

        $this->title = '';
    }

    public function toggleTask($id)
    {
        Task::where('id', $id)->update([
            'is_completed' => \DB::raw('NOT is_completed')
        ]);
    }

    public function deleteTask($id)
    {
        Task::destroy($id);
    }

    public function clearCompleted()
    {
        Task::where('is_completed', true)->delete();
    }

    public function render()
    {
        return view('livewire.todo-list', [
            'tasks' => Task::latest()->get(),
        ]);
    }
}
