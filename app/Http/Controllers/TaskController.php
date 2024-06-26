<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::latest()->paginate(10);
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'priority' => 'required|in:baja,media,alta', // Validación para asegurar que la prioridad sea una de las opciones permitidas
        ]);

        Task::create([
            'title' => $request->title,
            'priority' => $request->priority,
            'completed' => false,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|max:255',
            'priority' => 'required|in:baja,media,alta',
            'completed' => 'required',
        ]);

        $task->update([
            'title' => $request->title,
            'priority' => $request->priority,
            'completed' => $request->completed,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }

    public function complete(Task $task)
    {
        $task->update(['completed' => true]);

        return redirect()->route('tasks.index')->with('success', 'Task marked as completed.');
    }
}
