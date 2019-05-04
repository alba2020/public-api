<?php

namespace App\Http\Controllers;

use App\Action;
use App\Comment;
use App\Exceptions\CreateActionsException;
use App\Services\FakeService;
use App\Services\InstagramService;
use App\Services\NakrutkaService;
use App\Status;
use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class TasksController extends Controller
{
    public function index()
    {
        return Task::with(['actions', 'comments'])->get()->all();
    }

    public function show(Task $task)
    {
        $t = Task::find($task->id)->with(['actions', 'comments'])->first();
        return $t;
    }

    public function store(Request $request, NakrutkaService $nakrutka) {
        $allowedTypes = array_merge(FakeService::$types, InstagramService::$types);

        $validator = Validator::make($request->all(), [
            'platform' => ['required', Rule::in(['fake', 'instagram'])],
            'n' => 'required|integer|min:1|max:100', // total likes
            'speed' => 'required|integer|min:1|max:9',
            'type' => ['required', Rule::in($allowedTypes)],
            'url' => 'required|url',
            'local' => 'integer' // local users to use
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()],
                Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // todo check available workers before creating task
        // todo make order in nakrutka if needed
        // todo check instagram?

        $task = new Task();
        $task->fill($request->except('comments'));
        $task->owner_id = Auth::user()->id;
        $task->status = Status::CREATED;

//        todo test
//        $local = $request->input('local');
//        if ($local) {
//          $remote = $task->n - $local;
//          $task->n = $local;
//          $nakrutka->add($task->url, $remote);
//        }

        $task->save();

        // create comments for task
        foreach($request->comments as $comment) {
            Comment::create(['text' => $comment, 'task_id' => $task->id]);
        }

        try {
            $task->createActions();
        } catch (CreateActionsException $ex) {
            return response()->json([
                'error' => 'create actions error',
                'message' => $ex->getMessage()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $taskWithData = Task::find($task->id)
                            ->with(['actions', 'comments'])
                            ->first();

        return response()->json($taskWithData, Response::HTTP_CREATED);
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

    public function runPlatform($platform)
    {
        $numberOfActions = Task::run($platform);

        return response()->json([
            'message' => 'ok',
            'platform' => $platform,
            'actions' => $numberOfActions
        ], Response::HTTP_OK);
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

    /**
     * @param Task $task
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function undo(Task $task)
    {
        foreach($task->actions as $action) {
            $action->status = Status::CREATED;
            $action->save();
        }

        $reverse = [
            'like' => 'unlike',
            'unlike' => 'like',
            'follow' => 'unfollow',
            'unfollow' => 'follow',
            'comment' => 'uncomment',
            'uncomment' => 'comment'
        ];

//        if($task->type == 'like')
//            $task->type = 'unlike';
//        else if($task->type == 'unlike')
//            $task->type = 'like';
//        else if($task->type == 'follow')
//            $task->type = 'unfollow';
//        else if($task->type == 'unfollow')
//            $task->type = 'follow';
//        else
//            throw new \Exception('unknown action type');

        $task->type = $reverse[$task->type];
        $task->status = Status::CREATED;
        $task->save();
        return response()->json(['message' => 'undo'], Response::HTTP_OK);
    }
}
