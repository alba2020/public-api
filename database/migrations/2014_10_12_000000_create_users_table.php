<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('confirmation_code')->nullable();
            $table->string('reset_code')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->string('vk_id')->nullable();
            $table->string('vk_token')->nullable();

            $table->string('fb_id')->nullable();
            $table->string('fb_token')->nullable();

            $table->string('fake_login')->nullable();

            $table->string('instagram_login')->nullable();
            $table->string('instagram_password')->nullable();
            $table->integer('instagram_proxy_id')->nullable();

            $table->text('roles')->nullable();

            $table->integer('wallet_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
