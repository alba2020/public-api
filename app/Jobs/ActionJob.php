<?php

namespace App\Jobs;

use App\Action;
use App\Services\FakeService;
use App\Services\InstagramService;
use App\Status;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ActionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $action;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Action $action)
    {
        $this->action = $action;
    }

    public function fake_like(Action $action)
    {
        $fakeService = app(FakeService::class);
        $fakeService->like($action->worker, $action->task->url);
    }

    public function fake_unlike(Action $action)
    {
        $fakeService = app(FakeService::class);
        $fakeService->unlike($action->worker, $action->task->url);
    }

    public function instagram_like(Action $action)
    {
        echo "*** instagram like action ***\n";
        $instagramService = new InstagramService($action->worker);
        $instagramService->like($action->task->url);
    }

    public function instagram_unlike(Action $action)
    {
        echo "*** instagram unlike action ***\n";
        $instagramService = new InstagramService($action->worker);
        $instagramService->unlike($action->task->url);
    }

    public function instagram_follow(Action $action)
    {
        echo "*** instagram follow action ***\n";
        $instagramService = new InstagramService($action->worker);
        $instagramService->follow($action->task->url);
    }

    public function instagram_unfollow(Action $action)
    {
        echo "*** instagram unfollow action ***\n";
        $instagramService = new InstagramService($action->worker);
        $instagramService->unfollow($action->task->url);
    }

    public function instagram_comment(Action $action)
    {
        echo "*** instagram comment action ***\n";
        $instagramService = new InstagramService($action->worker);
        $comment = $action->task->comments()->inRandomOrder()->first();
        $commentId = $instagramService->comment($action->task->url, $comment->text);
        $action->instagram_comment_id = $commentId;
        $action->save();
    }

    public function instagram_uncomment(Action $action)
    {
        echo "*** instagram uncomment action ***\n";
        $instagramService = new InstagramService($action->worker);
        $instagramService->uncomment($action->task->url, $action->instagram_comment_id);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $action = $this->action;
        $task = $action->task;

        $worker_id = $action->worker_id;
        $action_id = $action->id;
        $task_id = $task->id;
        $type = $task->type;
        $url = $task->url;

        $msg = "Task #$task_id, Worker #$worker_id -- ";
        $msg .= "$type $url (Action #$action_id)\n";
        echo $msg;

        // --- action ---
        $methodName = $task->platform . '_' . $task->type;
        try {
            $this->$methodName($action);
            $action->status = Status::COMPLETED;
        } catch (\Exception $ex) {
            echo "\n=== action method exception===\n";
            $action->status = Status::ERROR;
        }
        // ---------
        $action->save();

        if ($task->getIncompleteActions() == 0) {
            $task->status = Status::COMPLETED;
            $task->save();
            echo "Task #$task_id is completed.\n";
        }
        sleep(rand(2, 6));
    }
}
