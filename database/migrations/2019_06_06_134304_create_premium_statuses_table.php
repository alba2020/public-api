<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePremiumStatusesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('premium_statuses', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');

            $table->boolean('online_support')->default(0);
            $table->boolean('friends_cashback')->default(0);
            $table->boolean('event_bonuses')->default(0);

            $table->integer('discount_likes')->default('0');
            $table->integer('discount_views')->default('0');
            $table->integer('discount_subs')->default('0');
            $table->integer('discount_rest')->default('0');

            $table->boolean('premium_services')->default(0);
            $table->boolean('bonus_five_percent')->default(0);
            $table->boolean('personal_manager')->default(0);

            $table->integer('cash')->default('0');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('premium_statuses');
    }
}
