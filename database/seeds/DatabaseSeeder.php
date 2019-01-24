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
         //       UPDATE: done in toContentArray() methods.
         $this->loadJSONseeds();
         self::genFakeStuff();
         $this->call(UserTableSeeder::class);
         
    }

    ///

    static protected function genFakeStoreShelves(
        $faker, int $numSections, int $numCategories, 
        int $numProducts
    ) {
        // for each section gen an article & a image..
        // then for each section create it categories..
        $sections = [];
        for ($i = 0; $i < $numSections; $i++) {
            if (Functions::testVar($ts = self::genFakeSection($faker))) {
                $sections[] = $ts;
            }
        }
        //dd($sections);
        foreach ($sections as $section) {
            $categories = [];
            for ($i = 0; $i < $numCategories; $i++) {
                if (Functions::testVar($tc = self::genFakeCategory($faker, null, null, $section))) {
                    $categories[] = $tc;
                }
            }
            //dd($categories);
            foreach ($categories as $category) {
                $products = [];
                for ($j = 0; $j < $numProducts; $j++) {
                    $tPrc = $faker->randomFloat(2, 1.0, 200.0);
                    if (Functions::testVar($tp = self::genFakeProduct($faker, null, null, $category, $tPrc))) {
                        $products[] = $tp;
                    }
                }
                //dd($products);
            }
        }
    }

    static protected function genFakeProduct(
        $faker, $img = null, $article = null, $category = 1,
        float $price = 0.0
    ) {
        $tImg = Functions::getVar($img, self::genFakeImage($faker));
        $tArt = Functions::getVar($article, self::genFakeArticle($faker, $tImg));
        $tPrc = Functions::getVar($price, $faker->randomFloat(2, 1.5, 200.0));
        $tSlp = random_int(1, 9) % 3 === 0 ? $faker->randomFloat(2, 1.5, $tPrc) : $tPrc;
        $stck = random_int(1, 9);
        if ($stck % 3 === 0) {
            $sticker = 'sticker-sale';
        } elseif ($stck % 3 == 1) {
            $sticker = 'sticker-new';
        } else {
            $sticker = '';
        }
        $tmp = Product::createNew(
            $faker->name, $faker->domainWord, $tPrc, 
            $tSlp, $category, $sticker, $tImg, 
            $faker->text(100), $faker->text(40), $tArt
        );
        return Functions::getVar($tmp, null);
    }

    static protected function genFakeCategory(
        $faker, $img = null, $article = null, $section = 1
    ) {
        $tImg = Functions::getVar($img, self::genFakeImage($faker));
        $tArt = Functions::getVar($article, self::genFakeArticle($faker, $tImg));
        $stck = random_int(1, 9);
        if ($stck % 3 === 0) {
            $sticker = 'sticker-sale';
        } elseif ($stck % 3 == 1) {
            $sticker = 'sticker-new';
        } else {
            $sticker = '';
        }
        $tmp = Categorie::createNew(
            $faker->name, $faker->domainWord, $faker->text(100), 
            $faker->text(40), $tArt, $section,
            $tImg, $sticker
        );
        return Functions::getVar($tmp, null);
    }

    static protected function genFakeSection(
        $faker, $img = null, $article = null, $catalog = 1
    ) {
        $tImg = Functions::getVar($img, self::genFakeImage($faker));
        $tArt = Functions::getVar($article, self::genFakeArticle($faker, $tImg));
        $tmp = Section::createNew(
            $faker->name, $faker->domainWord, $faker->text(40), $tArt,
            $faker->text(100), $tImg, $catalog
        );
        return Functions::getVar($tmp, null);
    }

    static protected function genFakeArticle($faker, $img = 1)
    {
        $tImg = random_int(1, 6) % 2 == 0 ? self::genFakeImage($faker) : $img;
        $tmp = Article::createNew(
            $faker->text(2000), $faker->text(80),
            $tImg, $faker->text(100), true
        );
        return Functions::getVar($tmp, null);
    }

    static protected function genFakeImage($faker)
    {
        $tmp = Image::createNew(
            $faker->imageUrl(), '', $faker->text(20), 
            $faker->text(80)
        );
        return Functions::getVar($tmp, null);
    } 

    static protected function genFakePages($faker, int $numPages)
    {
        // generate fake articles..
        $ass = [];
        for ($i = 0; $i < $numPages; $i++) {
            $tImg = random_int(1, 6) % 2 == 0 
                ? self::genFakeImage($faker) 
                : Image::getRandomImage();
            if (Functions::testVar($tmp = self::genFakeArticle($faker, $tImg))) {
                //dd($tmp);
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
                $tImg1 = random_int(1, 6) % 2 == 0 
                    ? self::genFakeImage($faker) 
                    : Image::getRandomImage();
                $tmp = Page::createNew(
                    $pn[0], $pn[1], $tImg1,
                    $faker->text(10), $key, 
                    $faker->text(30), 1, '', 
                    -1, -1
                );
                //dd($tmp, $key, $pn);
            }
            //dd($ass, "dumpASS!");
        }
    }

    static protected function genFakeStuff()
    {
        $faker = \Faker\Factory::create();
        $num_a = 20;
        self::genFakePages($faker, $num_a);
        $num_sect = $faker->numberBetween(3, 30);
        $num_cat = $faker->numberBetween(6, 30);
        $num_prod = $faker->numberBetween(9, 30);
        self::genFakeStoreShelves($faker, $num_sect, $num_cat, $num_prod);
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
