<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Take5 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smm:take5';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Touch file in 5 minutes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        exec('touch take5.txt');
    }
}
