<div class="min-h-screen bg-indigo-900 py-12 px-4 font-sans">
    <div class="max-w-md mx-auto bg-white border-8 border-yellow-400 rounded-3xl shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] p-8 transform -rotate-1">
        <h1 class="text-5xl font-black text-indigo-600 mb-8 text-center tracking-tighter uppercase italic">
            My Funky <br> Todo List ⚡
        </h1>

        <form wire:submit.prevent="addTask" class="flex gap-3 mb-8">
            <input
                type="text"
                wire:model="title"
                placeholder="What's the plan?..."
                class="flex-1 px-4 py-3 border-4 border-black rounded-xl focus:ring-0 focus:outline-none font-bold text-lg placeholder-gray-400"
            >
            <button
                type="submit"
                class="bg-pink-500 hover:bg-pink-600 text-white font-black px-6 py-3 rounded-xl border-4 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] active:shadow-none active:translate-x-1 active:translate-y-1 transition-all"
            >
                ADD!
            </button>
        </form>

        <div class="space-y-4">
            @foreach($tasks as $task)
                <div
                    wire:key="{{ $task->id }}"
                    class="group flex items-center justify-between p-4 border-4 border-black rounded-2xl transition-all hover:scale-105 {{ $task->is_completed ? 'bg-gray-200' : 'bg-yellow-100' }}"
                >
                    <div class="flex items-center gap-3">
                        <button
                            wire:click="toggleTask({{ $task->id }})"
                            class="w-6 h-6 border-4 border-black rounded-full flex items-center justify-center transition-colors {{ $task->is_completed ? 'bg-green-400' : 'bg-white' }}"
                        >
                            @if($task->is_completed)
                                <svg class="w-4 h-4 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            @endif
                        </button>
                        <span class="font-bold text-xl {{ $task->is_completed ? 'line-through text-gray-500' : 'text-black' }}">
                            {{ $task->title }}
                        </span>
                    </div>

                    <button
                        wire:click="deleteTask({{ $task->id }})"
                        class="opacity-0 group-hover:opacity-100 bg-red-500 text-white p-2 rounded-lg border-2 border-black hover:bg-red-600 transition-all"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @endforeach

            @if($tasks->isEmpty())
                <div class="text-center py-8 text-gray-400 font-bold italic">
                    Nothing to do! Party time! 🎉
                </div>
            @endif
        </div>

        @if($tasks->where('is_completed', true)->count() > 0)
            <button
                wire:click="clearCompleted"
                class="mt-8 w-full py-3 bg-indigo-500 text-white font-black rounded-xl border-4 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:bg-indigo-600 active:shadow-none active:translate-x-1 active:translate-y-1 transition-all"
            >
                CLEAN UP COMPLETED 🧹
            </button>
        @endif
    </div>
</div>
