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

    /**
     * Undocumented function
     *
     * @param array $sections
     * @param integer $catalog_id - WISHLIST ITEM!!!
     * @return void
     */
    static protected function loadSections(array $sections, int $catalog_id = 0)
    {
        // Sections
        if (Functions::testVar($sections??'')) {
            $bol = true;
            foreach ($sections as $sect) {
                // $sect['catalog_id'] = $catalog_id; // <<=- WISHLIST ITME!!
                $s = Section::createNewFrom($sect);
                if (Functions::testVar($s) 
                    && Functions::testVar($sect['categories']??'')
                ) {
                    if (!self::loadCategories($sect['categories'], $s)) {
                        $bol = false;
                    }
                } else {
                    $bol = false;
                }
            }
            return $bol;
        }
        return false;
    }

    static protected function loadCategories(array $categories, int $section_id = 0)
    {
        // Categories
        if (Functions::testVar($categories)) {
            $bol = true;
            foreach ($categories as $cat) {
                $cat['section_id'] = $section_id;
                $c = Categorie::createNewFrom($cat);
                if (Functions::testVar($c)
                && Functions::testVar($cat['products'])
                ) {
                    if (!self::loadProducts($cat['products'], $c)) {
                        $bol = false;
                    }
                } else {
                    $bol = false;
                }
            }
            return $bol;
        }
        return false;
    }

    static protected function loadProducts(array $products, int $category_id = 0)
    {
        if (Functions::testVar($products)) {
            $bol = true;
            // Products
            foreach ($products as $prod) {
                $prod['category_id'] = $category_id;
                $p = Product::createNewFrom($prod);
                if (!Functions::testVar($p)) {
                    $bol = false;
                }
            }
            return $bol;
        }
        return false;
    }

    public function loadJSONseeds()
    {
        $filepath = 'db/seedFile.json';// TODO! create file name & path!
        $disk = Storage::disk('local');
        if ($disk->exists($filepath)) {
            $content = $disk->get($filepath);
            if (Functions::testVar($content)) {
                $data = json_decode($content, true);
                if (Functions::testVar($data)) {
                    self::loadSections($data['sections'], 0);
                }
            }
            
        }
        
    }
}
