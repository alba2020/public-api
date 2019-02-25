<?php
//
//namespace Tests\Feature;
//
//use App\Task;
//use App\User;
//use Illuminate\Foundation\Testing\DatabaseMigrations;
//use Tests\TestCase;
//use Illuminate\Foundation\Testing\WithFaker;
//use Illuminate\Foundation\Testing\RefreshDatabase;
//
//class WorkersBenchmarkTest extends TestCase
//{
//    use DatabaseMigrations;
//
//    public function setUp()
//    {
//        parent::setUp();
//
//        factory(\App\User::class, 1000)->create();
//        factory(\App\Task::class, 1000)->create();
//        factory(\App\Action::class, 10000)->create();
//    }
//
//    public function testFirst()
//    {
//        $start = microtime(true);
//        ////////////////////
//
//        $task1 = Task::find(1);
//        $workers = User::fakeWorkers($task1);
//
//        ///////////////////
//        $time_elapsed_secs = microtime(true) - $start;
//        echo "\ntest time $time_elapsed_secs seconds\n";
//    }
//}
