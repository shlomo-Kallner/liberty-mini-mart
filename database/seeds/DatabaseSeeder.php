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
        $filepath = 'file.txt';// TODO! rewite file name & path!
        $disk = Storage::disk('local');
        if ($disk->exists($filepath)) {
            $content = $disk->get($filepath);
            if (Functions::testVar($content)) {
                $data = json_decode($content, true);
                if (Functions::testVar($data)) {
                    // Sections
                    if (Functions::testVar($data['sections']??'')) {
                        foreach ($data['sections'] as $sect) {
                            $t = Section::createNewFrom($sect);
                            if (Functions::testVar($t) 
                                && Functions::testVar($sect['categories']??'')
                            ) {
                                // Categories
                                foreach ($sect['categories'] as $cat) {
                                    $cat['section_id'] = $t;
                                    $c = Categorie::createNewFrom($cat);
                                    if (Functions::testVar($c)
                                    && Functions::testVar($cat['products'])
                                    ) {
                                        // Products
                                        foreach ($cat['products'] as $prod) {
                                            $prod['category_id'] = $c;
                                            $p = Product::createNewFrom($prod);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
        }
        
    }
}
