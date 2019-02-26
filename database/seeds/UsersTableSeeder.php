<?php

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
            'name' => 'Administrator',
            'email' => 'admin@admin.panel',
            'password' => bcrypt('secret'),
        ]);

        factory(User::class, 8)->create();

        User::create([
            'instagram_login' => 'OaidaEbaba7133',
            'instagram_password' => 'YjLJv8ZTrA'
        ]);



    }
}
