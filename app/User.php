<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
//    protected $fillable = [
//        'name', 'email', 'password',
//    ];

    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function AauthAcessToken() {
        return $this->hasMany('\App\OauthAccessToken');
    }

    public function tasks()
    {
        return $this->hasMany('App\Task', 'owner_id');
    }

    public function actions()
    {
        return $this->hasMany('App\Action', 'worker_id');
    }

    public function scopeFakeWorkers($query, $task)
    {
        $ids = self::whoDid($task->url, $task->type);

        return $query->where('fake_login', '!=', null)
                     ->where('id', '!=', $task->owner_id)
                     ->whereNotIn('id', $ids);
    }

    public static function whoDid($url, $type)
    {
        $ids = DB::table('tasks')
                ->where('tasks.url', $url)
                ->where('tasks.type', $type)
                ->join('actions', 'tasks.id', '=', 'actions.task_id')
                ->pluck('worker_id')
                ->all();
        return $ids;
    }
}
