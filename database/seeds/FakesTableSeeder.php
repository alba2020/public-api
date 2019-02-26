<?php

use App\Fake;
use Illuminate\Database\Seeder;

class FakesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Fake::create(['id' => 1, 'url' => 'http://fake1.url', 'likes' => '']);
        Fake::create(['id' => 2, 'url' => 'http://fake2.url', 'likes' => '']);
        Fake::create(['id' => 3, 'url' => 'http://fake3.url', 'likes' => '']);
    }
}
