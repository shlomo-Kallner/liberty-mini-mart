<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\Section;

class CreateSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('sections')) {
            Schema::table('sections', function (Blueprint $table) {
                if (!Schema::hasColumn('sections', 'id')) {
                    $table->increments('id');
                }
                if (!Schema::hasColumn('sections', 'name')) {
                    $table->string('name');
                }
                if (!Schema::hasColumn('sections', 'image')) {                
                    $table->string('image');
                }
                if (!Schema::hasColumn('sections', 'title')) {
                    $table->string('title');
                }
                if (!Schema::hasColumn('sections', 'sub_title')) {
                    $table->string('sub_title');
                }
                if (!Schema::hasColumn('sections', 'article')) {                
                    $table->text('article');
                }
                if (!Schema::hasColumn('sections', 'url')) {
                    $table->string('url')->unique();
                }
                if (!Schema::hasColumn('sections', 'description')) {
                    $table->string('description');
                }
                if (!Schema::hasColumn('sections', 'created_at') 
                    || !Schema::hasColumn('sections', 'updated_at')
                ) {
                    $table->timestamps();
                }
                if (!Schema::hasColumn('sections', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        } else {
            Schema::create(
                'sections', function (Blueprint $table) {
                    $table->increments('id');
                    $table->string('name');               
                    $table->string('image');
                    $table->string('title');
                    $table->string('sub_title');                
                    $table->text('article');
                    $table->string('url')->unique();
                    $table->string('description');
                    $table->timestamps();
                    $table->softDeletes();
                    
                }
            );
        }
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // save all data..
        //create our json file of db data..
        $filename = str_replace('.php', '', str_replace(__DIR__, '',  __FILE__) );

        if (Schema::hasTable('sections')) {
            $data = [];
            $columns = [];
            Schema::table('sections', function (Blueprint $table) {
                $columns = $table->getColumns();
    
            });
            $table->dropSoftDeletes();
        }
        
    }
}
