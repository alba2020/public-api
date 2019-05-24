<?php

use App\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->unique();
            $table->integer('user_id');
            $table->integer('service_id');
            $table->string('type')->nullable(); // == service->type
            $table->decimal('cost', 19, 4)->default('0.00');
            $table->decimal('price', 19, 4)->default('0.00');
            $table->string('status')->default(Order::STATUS_CREATED);

            // json not supported in MariaDB 10.1
            // array
            $table->text('details')->nullable(); // url, n etc.

            $table->boolean('paid')->default('0');
            $table->text('img')->nullable();
            $table->string('instagram_login')->nullable();

//            $table->text('link')->nullable();
            $table->integer('foreign_id')->nullable();
            // array
            $table->text('foreign_status')->nullable();

//          $table->integer('quantity')->default('0');
//          $table->integer('remains')->default('0');

//            $table->string('username')->nullable();
//            $table->integer('qmin')->nullable();
//            $table->integer('qmax')->nullable();
//            $table->integer('posts')->nullable();
//            $table->integer('delay')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
