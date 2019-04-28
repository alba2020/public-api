<?php

use App\Role\UserRole;
use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        User::create([
            'id' => 1,
            'name' => 'leonardo_alberti',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'fake_login' => 'xxx',
            'instagram_login' => 'leonardo_alberti',
            'instagram_password' => 'qHIEdOXg',
            'instagram_proxy_id' => 2,
        ]);

        User::create([
            'id' => 2,
            'name' => 'fashoin10',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'fake_login' => 'xxx',
            'instagram_login' => 'fashoin10',
            'instagram_password' => 'aNw5f6fm',
            'instagram_proxy_id' => 2,
        ]);

        User::create([
            'id' => 3,
            'name' => 'yana_koks',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'fake_login' => 'xxx',
            'instagram_login' => 'yana_koks',
            'instagram_password' => 'fpC4530O',
            'instagram_proxy_id' => 2,
        ]);

        User::create([
            'id' => 4,
            'name' => 'wifey312',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'fake_login' => 'xxx',
            'instagram_login' => 'wifey312',
            'instagram_password' => 't3DK7Qbn',
            'instagram_proxy_id' => 4,
        ]);

        User::create([
            'id' => 5,
            'name' => 'afef',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'fake_login' => 'xxx',
            'instagram_login' => 'afef',
            'instagram_password' => 'dBqQowJgAA',
            'instagram_proxy_id' => 4,
        ]);

        User::create([
            'id' => 6,
            'name' => 'king_finley',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'fake_login' => 'xxx',
            'instagram_login' => 'king_finley',
            'instagram_password' => '1sxV6pPx',
            'instagram_proxy_id' => 3,
        ]);

        User::create([
            'id' => 8,
            'name' => 'xochifonz',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'fake_login' => 'xxx',
            'instagram_login' => 'xochifonz',
            'instagram_password' => 'iy34S3iu',
            'instagram_proxy_id' => 3,
        ]);


        User::create([
            'id' => 1000,
            'name' => 'admin',
            'email' => 'admin@smm.example.com',
            'password' => bcrypt('secret'),
            'roles' => [UserRole::ROLE_ADMIN],
        ]);

        User::create([
            'id' => 1001,
            'name' => 'moder',
            'password' => bcrypt('secret'),
            'email' => 'moder@smm.example.com',
            'roles' => [UserRole::ROLE_MODERATOR],
        ]);

        User::create([
            'id' => 101,
            'name' => 'user1',
            'password' => bcrypt('secret'),
            'email' => 'user1@smm.example.com',
            'roles' => [UserRole::ROLE_VERIFIED],
        ]);

        User::create([
            'id' => 102,
            'name' => 'user2',
            'password' => bcrypt('secret'),
            'email' => 'user2@smm.example.com',
            'roles' => [UserRole::ROLE_VERIFIED],
        ]);

        User::create([
            'id' => 103,
            'name' => 'user3',
            'password' => bcrypt('secret'),
            'email' => 'user3@smm.example.com',
            'roles' => [UserRole::ROLE_VERIFIED],
        ]);

//        factory(User::class, 8)->create();
    }
}
