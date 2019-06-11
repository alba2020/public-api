<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {
        $this->call(ProxiesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(TasksTableSeeder::class);
        $this->call(FakesTableSeeder::class);
        $this->call(BotsTableSeeder::class);
        $this->call(ServicesTableSeeder::class);
        $this->call(TransactionsTableSeeder::class);
        $this->call(OrdersTableSeeder::class);
        $this->call(PremiumStatusesTableSeeder::class);

        $exitCode = Artisan::call('passport:install');
        echo "passport install " . $exitCode . "\n";
    }
}
