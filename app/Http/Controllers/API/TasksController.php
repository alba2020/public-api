<?php

namespace App\Http\Controllers\API;

use App\Action;
use App\Exceptions\CreateActionsException;
use App\Http\Controllers\Controller;
use App\Jobs\ActionJob;
use App\Jobs\DoTaskJob;
use App\Status;
use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TasksController extends Controller
{
    public function index()
    {
        return Task::with('actions')->get()->all();
    }

    public function show(Task $task)
    {
        $t = Task::where('id', $task->id)->with('actions')->first();
        return $t;
    }

    public function store(Request $request)
    {
        $task = new Task();
        $task->fill($request->all());
        $task->owner_id = Auth::user()->id;
        $task->status = Status::CREATED;
        $task->save();

        try {
            $task->createActions();
        } catch (CreateActionsException $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $taskWithActions = Task::where('id', $task->id)
                            ->with('actions')
                            ->first();

        return response()->json($taskWithActions, Response::HTTP_CREATED);
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

    public function runFake()
    {
        $tasks = Task::where('platform', 'fake')
                    ->where('type', 'like')
                    ->where('status', Status::CREATED)
                    ->get()
                    ->all();

        foreach($tasks as $task) {
//            todo optimize
//            $job = new DoTaskJob();
//            DoTaskJob::dispatch($task)->onQueue('fake'); // dispatch($job);
            $actions = $task->actions()->get()->all();
            foreach($actions as $a) {
                ActionJob::dispatch($a)->onQueue('fake');
            }
        }

        return response()->json(['message' => 'run tasks ' . count($tasks)],
            Response::HTTP_OK);
    }

    public function resetAll()
    {
        foreach(Action::all() as $a) {
            $a->status = Status::CREATED;
            $a->save();
        }

        foreach(Task::all() as $t) {
            $t->status = Status::CREATED;
            $t->save();
        }

        return response()->json(['message' => 'reset'], Response::HTTP_OK);
    }
}
