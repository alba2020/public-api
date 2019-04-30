<?php

use App\Constants;
use App\Status;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bots', function (Blueprint $table) {
            $table->increments('id');

            $table->string('platform')->default(Constants::NOT_SET);
            $table->string('login');
            $table->string('password');

            $table->string('status')->default(Status::BOT_UNKNOWN);
            $table->boolean('approved')->default('0');

            $table->integer('proxy_id')->nullable();
            $table->integer('user_id')->nullable();

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
        Schema::dropIfExists('bots');
    }
}
