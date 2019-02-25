<?php

namespace App;

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

    /* @throws \App\Exceptions\CreateActionsException */
    public function createActions()
    {
        if ($this->platform == 'fake')
        {
            $availableUsers = User::fakeWorkers($this)->count();
            if ($availableUsers < $this->n)
            {
                throw new CreateActionsException('not enough workers');
            }

            $workers = User::fakeWorkers($this)
                            ->inRandomOrder()
                            ->take($this->n)
                            ->get()
                            ->all();

            foreach($workers as $worker)
            {
                Action::create([
                    'task_id' => $this->id,
                    'worker_id' => $worker->id,
                    'status' => Status::CREATED
                ]);
            }
        }
        else
        {
            throw new CreateActionsException('unknown platform');
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
}
