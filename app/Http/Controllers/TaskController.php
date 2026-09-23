<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::query()
            ->orderByRaw("CASE WHEN status = 'Pending' THEN 0 ELSE 1 END")
            ->orderBy('due_date')
            ->latest()
            ->get();

        return view('welcome', [
            'tasks' => $tasks,
            'pendingCount' => $tasks->where('status', 'Pending')->count(),
            'completedCount' => $tasks->where('status', 'Completed')->count(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', Rule::in(['Pending', 'Completed'])],
            'due_date' => ['nullable', 'date'],
        ]);

        Task::create($validated);

        return redirect()->route('tasks.index')->with('success', 'Task added to your list.');
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'task_name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', Rule::in(['Pending', 'Completed'])],
            'due_date' => ['nullable', 'date'],
        ]);

        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task removed.');
    }

    public function toggleStatus(Task $task)
    {
        $task->update([
            'status' => $task->status === 'Pending' ? 'Completed' : 'Pending',
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task status updated.');
    }
}
