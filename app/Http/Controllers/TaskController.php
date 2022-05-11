<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    public function index()
    {
        return TaskResource::collection(Task::with('user')->get());
    }

    public function store(Request $request)
    {
        $validatedInput = $request->validate([
            'user_id' => ['required'],
            'description' => ['required']
        ]);

        $task = Task::create($validatedInput)->fresh();

        return new TaskResource($task->load('user'));
    }

    public function update(Request $request, Task $task)
    {
        $validatedInput = $request->validate([
            'user_id' => ['sometimes', 'required'],
            'description' => ['sometimes', 'required'],
            'completed' => ['sometimes', 'required', 'bool']
        ]);

        $task->update($validatedInput);
        $task->load('user');

        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return response('', Response::HTTP_NO_CONTENT);
    }
}
