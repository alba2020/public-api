<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable {
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
        'roles' => 'array',
    ];

    public function AauthAcessToken() {
        return $this->hasMany('\App\OauthAccessToken');
    }

    public function tasks() {
        return $this->hasMany('App\Task', 'owner_id');
    }

    public function orders() {
        return $this->hasMany('App\Order');
    }

    public function actions() {
        return $this->hasMany('App\Action', 'worker_id');
    }

    public function bots() {
        return $this->hasMany('App\Bot');
    }

    public function wallet() {
        return $this->hasOne('App\Wallet')->withDefault();
    }

    public function scopeFakeWorkers($query, $task) {
        $ids = self::whoDid($task->url, $task->type);

        return $query->where('fake_login', '!=', null)
            ->where('id', '!=', $task->owner_id)
            ->whereNotIn('id', $ids);
    }

    public function scopeInstagramWorkers($query, $task) {
        $ids = self::whoDid($task->url, $task->type);

        return $query->where('instagram_login', '!=', null)
            ->where('id', '!=', $task->owner_id)
            ->whereNotIn('id', $ids);
    }

    public static function whoDid($url, $type) {
        $ids = DB::table('tasks')
            ->where('tasks.url', $url)
            ->where('tasks.type', $type)
            ->join('actions', 'tasks.id', '=', 'actions.task_id')
            ->pluck('worker_id')
            ->all();
        return $ids;
    }

    public function instagramProxy() {
        return $this->belongsTo('App\Proxy');
    }

    // ----------- roles ----------------

    /***
     * @param string $role
     * @return $this
     */
    public function addRole(string $role) {
        $roles = $this->getRoles();
        $roles[] = $role;

        $roles = array_unique($roles);
        $this->setRoles($roles);

        return $this;
    }

    /**
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles) {
        $this->setAttribute('roles', $roles);
        return $this;
    }

    /***
     * @param $role
     * @return mixed
     */
    public function hasRole($role) {
        return in_array($role, $this->getRoles());
    }

    /***
     * @param $roles
     * @return mixed
     */
    public function hasRoles($roles) {
        $currentRoles = $this->getRoles();
        foreach ($roles as $role) {
            if (!in_array($role, $currentRoles)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return array
     */
    public function getRoles() {
        $roles = $this->getAttribute('roles');

        if (is_null($roles)) {
            $roles = [];
        }

        return $roles;
    }
}
