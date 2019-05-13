<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('title');

            $table->string('startup'); // 'до 5 минут'
            $table->string('speed'); // '500-1000 в минуту'
            $table->string('details'); // детали
            $table->string('requirements'); // требования к аккаунту

            $table->integer('min');
            $table->integer('max');

            $table->integer('nakrutka_id');

            $table->decimal('price', 19, 4);
            $table->decimal('price_1k', 19, 4);
            $table->decimal('price_5k', 19, 4);
            $table->decimal('price_10k', 19, 4);
            $table->decimal('price_25k', 19, 4);
            $table->decimal('price_50k', 19, 4);
            $table->decimal('price_100k', 19, 4);

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
        Schema::dropIfExists('services');
    }
}
