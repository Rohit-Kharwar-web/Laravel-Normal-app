<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // Fetch all tasks
    public function index()
    {
        return response()->json(Task::all());
    }

    // Store a new task
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255'
        ]);

        $task = Task::create(['title' => $request->title]);

        return response()->json([
            'message' => 'Task created successfully!',
            'task' => $task
        ]);
    }

    // Show a single task
    public function show($id)
    {
        $task = Task::find($id);
        return response()->json($task);
    }

    // Update a task
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255'
        ]);

        $task = Task::findOrFail($id);
        $task->update(['title' => $request->title]);

        return response()->json([
            'message' => 'Task updated successfully!',
            'task' => $task
        ]);
    }

    // Delete a task
    public function destroy($id)
    {
        Task::destroy($id);

        return response()->json([
            'message' => 'Task deleted successfully!'
        ]);
    }
}

