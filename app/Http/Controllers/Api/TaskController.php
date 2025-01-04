<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Models\Tasks;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $tasks = Tasks::where('user_id', auth()->user()->id)->get();
        return response()->json($tasks , 200);

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {

        $task = Tasks::create([
            'user_id' => auth()->user()->id,
            'title' => $request['title'],
            'description' => $request['description'],
            'priority' => $request['priority'],
            'status' => $request['status'],
        ]);

        return response()->json($task , 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Tasks::find($id);
        return response()->json($task , 200);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required',
            'priority' => 'sometimes|required',
            'status' => 'sometimes|required',
        ]);

        $task = Tasks::find($id);
        $task->update($validated);

        return response()->json($task , 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Tasks::find($id);
        $task->delete();
        return response()->json(['message' => 'Task deleted'], 200);
    }
}
