<?php

use App\Constants;
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
            $table->string('group')->default(Constants::GROUP_OTHER);

            $table->string('startup'); // 'до 5 минут'
            $table->string('speed'); // '500-1000 в минуту'
            $table->string('details')->nullable(); // детали
            $table->string('requirements')->nullable(); // требования к аккаунту

            $table->text('info')->nullable(); // массив инфо

            $table->integer('min');
            $table->integer('max');

            $table->integer('nakrutka_id');

            // array
            $table->text('price_list')->nullable();

//            $table->decimal('price', 19, 4);
//            $table->decimal('price_1k', 19, 4);
//            $table->decimal('price_5k', 19, 4);
//            $table->decimal('price_10k', 19, 4);
//            $table->decimal('price_25k', 19, 4);
//            $table->decimal('price_50k', 19, 4);
//            $table->decimal('price_100k', 19, 4);

            $table->string('img')->nullable();
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
