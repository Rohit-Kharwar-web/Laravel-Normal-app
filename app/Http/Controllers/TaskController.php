<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // Fetch all tasks
    public function index(Request $request)
    {
       $t= Task::all();
        if($request->expectsJson()){
        return response()->json($t);
        }
        return view('tasks',['task'=>$t]);
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

        $task = Task::find($id);
        if ($task) {
            $task->update(['title' => $request->title]);
            if($request->expectsJson()){
               
                return response()->json([
                    'message' => 'Task updated successfully!',
                    'task' => $task
                ]);
            } 
            return redirect()->route('')->with('success','');
        } else{
            if($request->expectsJson()){
               
                return response()->json([
                    'message' => 'Task updated fail!',
                    'task' => $task
                ]);
            } 
            return redirect()->route('/404')->with('failed','');
        }
       
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

