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
            $table->string('name', 255)
                ->charset('utf8')
                ->collation('utf8_unicode_ci');
            $table->string('email', 255)->unique()
                ->charset('utf8')
                ->collation('utf8_unicode_ci');
            $table->string('password', 255)
                ->charset('utf8')
                ->collation('utf8_unicode_ci');
            //$table->string('image'); 
            $table->integer('image')->unsigned();
            $table->integer('plan_id')->unsigned();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            //$table->foreign('plan_id')->references('id')->on('plans');
            //$table->foreign('image')->references('id')->on('images');
            }
        );
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
