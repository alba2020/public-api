<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Jobs\DoTaskJob;
use Illuminate\Http\Request;
use App\Task;
use Symfony\Component\HttpFoundation\Response;

class TasksController extends Controller
{
    public function index()
    {
        return Task::all();
    }

    public function show(Task $task)
    {
        return $task;
    }

    public function store(Request $request)
    {
        $task = Task::create($request->all());

        return response()->json($task, Response::HTTP_CREATED);
    }

    public function update(Request $request, Task $task)
    {
        $task->update($request->all());

        return response()->json($task, Response::HTTP_OK);
    }

    public function delete(Task $task)
    {
        $task->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function run()
    {
        $tasks = Task::where('platform', 'fake')
                    ->where('type', 'like')
                    ->where('completed', 0)
                    ->get()
                    ->all();

        foreach($tasks as $task) {
//            $job = new DoTaskJob();
            DoTaskJob::dispatch($task)->onQueue('fake'); // dispatch($job);
        }

        return response()->json(['message' => 'run tasks'], Response::HTTP_OK);
    }

    public function reset()
    {
        foreach(Task::all() as $task) {
            $task->completed = 0;
            $task->save();
        }
        return response()->json(['message' => 'tasks reset'], Response::HTTP_OK);
    }
}
