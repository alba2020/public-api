<?php

use App\Proxy;
use Illuminate\Database\Seeder;

class ProxiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $login = 'gnxgos';
        $password = 'fhjZyNXyg4';
        $port = 24531;

        Proxy::truncate();

        Proxy::create([
            'id' => 1,
            'type' => 'http',
            'ip' => '171.22.183.157',
            'port' => $port,
            'login' => $login,
            'password' => $password,
        ]);

        Proxy::create([
            'id' => 2,
            'type' => 'http',
            'ip' => '45.66.15.214',
            'port' => $port,
            'login' => $login,
            'password' => $password,
        ]);

        Proxy::create([
            'id' => 3,
            'type' => 'http',
            'ip' => '141.98.169.190',
            'port' => $port,
            'login' => $login,
            'password' => $password,
        ]);

        Proxy::create([
            'id' => 4,
            'type' => 'http',
            'ip' => '109.248.135.37',
            'port' => $port,
            'login' => $login,
            'password' => $password,
        ]);

        Proxy::create([
            'id' => 5,
            'type' => 'http',
            'ip' => '5.252.188.205',
            'port' => $port,
            'login' => $login,
            'password' => $password,
        ]);
    }
}
