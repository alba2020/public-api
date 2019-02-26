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
use Illuminate\Validation\Rule;
use Validator;
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
        $validator = Validator::make($request->all(), [
            'platform' => ['required', Rule::in(['fake', 'instagram'])],
            'n' => 'required|integer|min:1|max:100',
            'speed' => 'required|integer|min:1|max:9',
            'type' => ['required', Rule::in(['like', 'dislike'])],
            'url' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()],
                Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // todo check available workers before creating task

        $task = new Task();
        $task->fill($request->all());
        $task->owner_id = Auth::user()->id;
        $task->status = Status::CREATED;
        $task->save();

        try {
            $task->createActions();
        } catch (CreateActionsException $ex) {
            return response()->json([
                'error' => 'create actions error',
                'message' => $ex->getMessage()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
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
        $numberOfActions = Task::run('fake');
        return response()->json(['message' => 'run actions ' . $numberOfActions],
            Response::HTTP_OK);
    }

    public function runInstagram()
    {
        $numberOfActions = Task::run('instagram');
        return response()->json(['message' => 'run actions ' . $numberOfActions],
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

    public function undo(Task $task)
    {
        foreach($task->actions as $action) {
            $action->status = Status::CREATED;
            $action->save();
        }

        if($task->type == 'like')
            $task->type = 'unlike';
        else if($task->type == 'unlike')
            $task->type = 'like';

        $task->status = Status::CREATED;
        $task->save();
        return response()->json(['message' => 'unlike'], Response::HTTP_OK);
    }
}
