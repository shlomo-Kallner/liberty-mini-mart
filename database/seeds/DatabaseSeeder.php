<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call(UserTableSeeder::class);

         /// TODO: write a function that will load the initial data 
         //     from a json file to be placed at 'public\site\db\seeds'
         //     AND create and fill such a JSON file!!!
         // NOTE: rewrite Image file name+path getters to check if
         //         'path' is empty, if so -> the image is non-local!
         //         else -> image is local, so append 'path' to the 'name'!
    }
}
