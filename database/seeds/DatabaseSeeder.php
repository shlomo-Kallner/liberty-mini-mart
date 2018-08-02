<?php

use Illuminate\Database\Seeder;
use App\Utilities\Functions\Functions;
use Illuminate\Support\Facades\Storage;

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
         $this->loadJSONseeds();
    }

    public function loadJSONseeds()
    {
        $filepath = 'file.txt';
        $disk = Storage::disk('local');
        if ($disk->exists($filepath)) {
            $content = $disk->get($filepath);
            if (Functions::testVar($content)) {
                $data = json_decode($content);
                if (Functions::testVar($data)) {
                    // Sections
                    if (Functions::testVar($data['sections']??'')) {
                        foreach ($data['sections'] as $item) {
                            Section::createNewFrom($item);
                        }
                    }
                    // Categories
                    if (Functions::testVar($data['categories']??'')) {
                        foreach ($data['categories'] as $item) {
                            
                        }
                    }
                    // Products
                    if (Functions::testVar($data['products']??'')) {
                        foreach ($data['products'] as $item) {
                            
                        }
                    }
                }
            }
            
        }
        
    }
}
