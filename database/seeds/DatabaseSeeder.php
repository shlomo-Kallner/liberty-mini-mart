<?php

use Illuminate\Database\Seeder;
use App\Utilities\Functions\Functions;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Storage;
use App\Image;
use App\Article,
    App\Page,
    App\Product,
    App\Plan;
use App\Section;
use App\Categorie;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         /// TODO: write a function that will load the initial data 
         //     from a json file to be placed at 'public\site\db\seeds'
         //     AND create and fill such a JSON file!!!
         // NOTE: rewrite Image file name+path getters to check if
         //         'path' is empty, if so -> the image is non-local!
         //         else -> image is local, so append 'path' to the 'name'!
         $this->loadJSONseeds();
         self::genFakeStuff();
         $this->call(UserTableSeeder::class);
         
    }

    static protected function genFakeStuff()
    {
        $faker = \Faker\Factory::create();
        // generate fake articles..
        $num_a = 20;
        $ass = [];
        for($i = 0; $i < $num_a; $i++) {
            $tmp = Article::createNew(
                $faker->text(200),
                $faker->text(8),
                1,
                $faker->text(30),
                true
            );
            //dd($tmp);
            if (Functions::testVar($tmp)) {
                $ass[$tmp] = [
                    $faker->name,
                    $faker->domainWord
                ];
            }

        }
        //dd($ass);
        // generate fake pages..
        if (Functions::testVar($ass)) {
            foreach ($ass as $key => $pn) {
                //dd($key, $pn);
                $tmp = Page::createNew(
                    $pn[0], $pn[1], 1,
                    $faker->text(10), $key, 
                    $faker->text(30), 1, ''
                );
                //dd($tmp, $key, $pn);
            }
        }
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

    static protected function loadImages(array $images)
    {
        if (Functions::testVar($images)) {
            $bol = true;
            // Products
            foreach ($images as $img) {
                $i = Image::createNewFrom($img);
                if (!Functions::testVar($i)) {
                    $bol = false;
                }
            }
            return $bol;
        }
        return false;
    }

    static protected function loadPages(array $pages)
    {
        if (Functions::testVar($pages)) {
            $bol = true;
            // Products
            foreach ($pages as $page) {
                $p = Page::createNewFrom($page);
                if (!Functions::testVar($p)) {
                    $bol = false;
                }
            }
            return $bol;
        }
        return false;
    }

    static protected function loadArticles(array $articles)
    {
        if (Functions::testVar($articles)) {
            $bol = true;
            // Products
            foreach ($articles as $article) {
                $a = Article::createNewFrom($article);
                if (!Functions::testVar($a)) {
                    $bol = false;
                }
            }
            return $bol;
        }
        return false;
    }

    static protected function loadPlans(array $plans)
    {
        if (Functions::testVar($plans)) {
            $bol = true;
            // Products
            foreach ($plans as $plan) {
                $p = Plan::createNewFrom($plan);
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
                    self::loadImages($data['images']);
                    //self::loadArticles($data['articles']);
                    //self::loadPages($data['pages']);
                    //self::loadSections($data['sections'], 0);

                    //self::loadPlans($data['plans']);
                    
                }
            }
            
        }
        
    }
}
