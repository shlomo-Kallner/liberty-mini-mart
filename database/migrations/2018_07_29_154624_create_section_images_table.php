<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectionImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section_images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('image')->unsigned();
            $table->integer('section')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            //$table->foreign('image')->references('id')->on('images');
            //$table->foreign('section')->references('id')->on('sections');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('section_images');
    }
}
