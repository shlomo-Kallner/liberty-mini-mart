<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('image')->unsigned();
            $table->integer('page')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            //$table->foreign('image')->references('id')->on('images');
            //$table->foreign('page')->references('id')->on('pages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('page_images');
    }
}