<?php

namespace App\Console\Commands;

use App\Order;
use App\Services\NakrutkaService;
use Illuminate\Console\Command;

class UpdateOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smm:update_orders {--save}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update running orders';

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
        $runningOrders = Order::where('status', Order::STATUS_RUNNING)->get()->all();

        if (empty($runningOrders)) {
            echo "No running orders\n";
            return;
        }

        $req = [];

        foreach($runningOrders as $order) {
            $req[] = $order->foreign_id;//add nakrutka ids to request
        }

        $nakrutka = resolve(NakrutkaService::class);
        $res = $nakrutka->multiStatus($req);

        foreach($runningOrders as $order) {
            $foreign_id = $order->foreign_id;
            $n_status = $res->$foreign_id->status;
            echo "order $order->id $order->uuid $foreign_id $order->status $n_status\n";

            if ($this->option('save')) {
                $order->updateData($res);
            }
        }

        // In progress - выполняется
        // Pending - ожидает
        // Processing -

        // Partial - частично выполнен. возврат.

        // Canceled - отменен

        // Completed - выполнен

        print_r($res);
    }
}
