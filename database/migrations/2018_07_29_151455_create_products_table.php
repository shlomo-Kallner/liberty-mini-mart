<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->integer('image')->unsigned();
            $table->string('title', 255);
            $table->mediumText('article');
            $table->string('url', 255);
            $table->integer('category_id')->unsigned();
            $table->decimal('price', 12, 2);
            $table->decimal('sale', 12, 2)->nullable();
            $table->string('sticker', 255);
            $table->string('description', 255);
            $table->timestamps();
            $table->softDeletes();

            //$table->foreign('image')->references('id')->on('images');
            //$table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
}
