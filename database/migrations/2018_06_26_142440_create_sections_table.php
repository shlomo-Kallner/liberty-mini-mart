<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\Section;

class CreateSectionsTable extends Migration
{

    static public function save_data()
    {
        // save all data..
        //create our json file of db data..
        $filename = str_replace('.php', '', str_replace(__DIR__, '',  __FILE__) );
        $filename .= '_' . date('D_jS-M-Y_H-i-s-a') . '.json';
        $data = Section::all();
        //dd();
        Storage::disk('local')->put('migrations/' . $filename, $data);
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'sections', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 255);               
                $table->integer('image')->unsigned();
                $table->string('title', 255);
                $table->string('sub_title', 255);                
                $table->text('article', 255);
                $table->string('url', 255)->unique();
                $table->string('description', 255);
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('image')->references('id')->on('images');
                
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
        self::save_data();
        if (Schema::hasTable('sections')) {
            Schema::table('sections', function (Blueprint $table) {
                $table->dropSoftDeletes();
    
            });
        }
    }
}
