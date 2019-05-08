<?php

use App\Status;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->string('status')->default(Status::CREATED);
            // json not supported in MariaDB 10.1
            $table->text('details')->nullable(); // url, n etc.
            $table->boolean('paid')->default('0');
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
