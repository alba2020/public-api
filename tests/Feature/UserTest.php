<?php

namespace Tests\Feature;

use App\Action;
use App\Status;
use App\Task;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        User::create(['id' => 1, 'name' => 'administrator']);
        User::create(['id' => 2, 'name' => 'user 2', 'fake_login' => 'xxx']);
        User::create(['id' => 3, 'name' => 'user 3', 'fake_login' => 'xxx']);
        User::create(['id' => 4, 'name' => 'user 4', 'fake_login' => 'xxx']);
        User::create(['id' => 5, 'name' => 'user 5', 'fake_login' => 'xxx']);
        User::create(['id' => 6, 'name' => 'user 6', 'fake_login' => 'xxx']);
        User::create(['id' => 7, 'name' => 'user 7', 'fake_login' => 'xxx']);


        Task::create([
            'id' => 1,
            'owner_id' => 5,
            'platform' => 'fake',
            'url' => 'http://fake1',
            'type' => 'like',
            'n' => 1, 'speed' => 1,
            'status' => Status::CREATED]);

        Task::create([
            'id' => 2,
            'owner_id' => 6,
            'platform' => 'fake',
            'url' => 'http://fake2',
            'type' => 'like',
            'n' => 1, 'speed' => 1,
            'status' => Status::CREATED]);

        Task::create([
            'id' => 3,
            'owner_id' => 1,
            'platform' => 'fake',
            'url' => 'http://fake3',
            'type' => 'like',
            'n' => 1, 'speed' => 1,
            'status' => Status::CREATED]);

        Task::create([
            'id' => 4,
            'owner_id' => 3,
            'platform' => 'fake',
            'url' => 'http://fake1',
            'type' => 'comment',
            'n' => 1, 'speed' => 1,
            'status' => Status::CREATED]);

        Action::create(['id' => 1, 'task_id' => 1, 'worker_id' => 2,
            'status' => Status::CREATED]);
        Action::create(['id' => 2, 'task_id' => 2, 'worker_id' => 3,
            'status' => Status::CREATED]);
        Action::create(['id' => 3, 'task_id' => 2, 'worker_id' => 2,
            'status' => Status::CREATED]);
    }


    public function testHasAdministrator()
    {
        $this->assertDatabaseHas('users', [
            'name' => 'administrator'
        ]);
    }

    public function testHasNumberOfUsers()
    {
        $this->assertEquals(User::count(), 7);
        $this->assertNotEquals(User::count(), 8);
    }

    public function testHasNumberOfTasks()
    {
        $this->assertEquals(Task::count(), 4);
    }

    public function testHasTwoTasksWithSameUrl()
    {
        $this->assertDatabaseHas('tasks', [
            'id' => 1,
            'url' => 'http://fake1'
        ]);

        $this->assertDatabaseHas('tasks', [
            'id' => 4,
            'url' => 'http://fake1'
        ]);
    }

    public function testHasNumberOfActions()
    {
        $this->assertEquals(Action::count(), 3);
    }

    public function testWhoDidAction1()
    {
        $res = User::whoDid('http://fake1', 'like');
        $this->assertEquals($res, [2]);
    }

    public function testWhoDidAction2()
    {
        $res = User::whoDid('http://fake2', 'like');
        foreach([2,3] as $item) {
            $this->assertContains($item, $res);
        }
        foreach([1,4,5,6,7] as $item) {
            $this->assertNotContains($item, $res);
        }
    }

    public function testWhoDidAction3()
    {
        $res = User::whoDid('http://fake3', 'like');
        $this->assertEmpty($res);
    }

    public function testWorkersForTask1()
    {
        $task1 = Task::find(1);
        $workers = User::fakeWorkers($task1)->pluck('id');

        $this->assertEquals(4, count($workers));

        foreach([3,4,6,7] as $id) {
            $this->assertContains($id, $workers);
        }

        foreach([1,2,5] as $id) {
            $this->assertNotContains($id, $workers);
        }
    }

    public function testWorkersForTask2()
    {
        $task2 = Task::find(2);
        $workers = User::fakeWorkers($task2)->pluck('id');

        $this->assertEquals(3, count($workers));

        foreach([4,5,7] as $id) {
            $this->assertContains($id, $workers);
        }

        foreach([1,2,3,6] as $id) {
            $this->assertNotContains($id, $workers);
        }
    }

    public function testWorkersForTask3()
    {
        $task3 = Task::find(3);
        $workers = User::fakeWorkers($task3)->pluck('id');

        $this->assertEquals(6, count($workers));

        foreach([2,3,4,5,6,7] as $id) {
            $this->assertContains($id, $workers);
        }

        foreach([1] as $id) {
            $this->assertNotContains($id, $workers);
        }
    }

    public function testWorkersForTask4()
    {
        $task4 = Task::find(4);
        $workers = User::fakeWorkers($task4)->pluck('id');

        $this->assertEquals(5, count($workers));

        foreach([2,4,5,6,7] as $id) {
            $this->assertContains($id, $workers);
        }

        foreach([1,3] as $id) {
            $this->assertNotContains($id, $workers);
        }
    }
}
