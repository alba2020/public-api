<?php

namespace App;

use App\Jobs\ActionJob;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\CreateActionsException;

class Task extends Model
{
    protected $guarded = [];

    public function owner()
    {
        return $this->belongsTo('App\User', 'owner_id');
    }

    public function actions()
    {
        return $this->hasMany('App\Action');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    /* @throws \App\Exceptions\CreateActionsException */
    public function createActions()
    {
        if ($this->platform == 'fake') {
            $workers = User::fakeWorkers($this)
                ->inRandomOrder()
                ->take($this->n)
                ->get()
                ->all();
        } else if ($this->platform == 'instagram') {
            $workers = User::instagramWorkers($this)
                ->inRandomOrder()
                ->take($this->n)
                ->get()
                ->all();
        }
        else {
            throw new CreateActionsException('unknown platform');
        }

        if (count($workers) < $this->n) {
            throw new CreateActionsException('not enough workers');
        }

        foreach($workers as $worker) {
            Action::create([
                'task_id' => $this->id,
                'worker_id' => $worker->id,
                'status' => Status::CREATED
            ]);
        }
    }

    public function getIncompleteActions()
    {
        //todo optimize
        $res = 0;
        foreach($this->actions as $a) {
            if ($a->status != Status::COMPLETED) {
                $res++;
            }
        }
        return $res;
    }

    /**
     * Runs tasks for selected platform
     *
     * @param string $platform
     * @param string $type
     * @return int
     */
    public static function run($platform, $type='')
    {
        // todo optimize one sql request
        if ($type) {
            $tasks = Task::where('platform', $platform)
                ->where('type', $type)
                ->where('status', Status::CREATED)
                ->get()
                ->all();
        } else {
            $tasks = Task::where('platform', $platform)
                ->where('status', Status::CREATED)
                ->get()
                ->all();
        }

        $n = 0;
        foreach($tasks as $task) {
            foreach($task->actions as $action) {
                // queue name = platform
                ActionJob::dispatch($action)->onQueue($task->platform);
                $n++;
            }
        }
        return $n;
    }
}
