<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('url', 255);
            $table->string('title', 255);
            $table->string('description', 255);
            $table->integer('image_id')->unsigned();
            $table->integer('article_id')->unsigned()->nullable();
            $table->integer('visible')->unsigned()->nullable();
            $table->string('sticker', 255);
            $table->timestamps();
            $table->softDeletes();

            //$table->foreign('image')->references('id')->on('images');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pages', function (Blueprint $table) {
            //
        });
    }
}
