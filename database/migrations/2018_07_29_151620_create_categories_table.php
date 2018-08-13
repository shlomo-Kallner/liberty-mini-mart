<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->integer('image_id')->unsigned()->nullable();
            $table->string('title', 255);
            $table->integer('article_id')->unsigned()->nullable();
            $table->string('url', 255);
            $table->integer('section_id')->unsigned();
            $table->string('sticker', 255);
            $table->string('description', 255);
            $table->timestamps();
            $table->softDeletes();

            //$table->foreign('image')->references('id')->on('images');
            //$table->foreign('section_id')->references('id')->on('sections');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            //
        });
    }
}
