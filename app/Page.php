<?php

namespace App;

use Illuminate\Database\Eloquent\Model, 
    Illuminate\Http\Request,
    App\Utilities\Functions\Functions,
    App\Utilities\ContainerTransforms,
    App\Utilities\TransformableContainer,
    App\Utilities\ContainerAPI,
    App\Utilities\ContainerID,
    App\Utilities\LinkGenerator,
    DB,
    App\Article,
    App\Section,
    App\Categorie,
    App\PageGroup,
    App\PageGrouping,
    App\User,
    Session;

class Page extends Model implements TransformableContainer
{
    use ContainerTransforms, LinkGenerator;

    ///

    static public function getNameForURL($url)
    { 
        // to be implemented with a db query!
        //return $url === '' ? 'index' : '' ;
        if (is_string($url)) {
            $tmp = self::where('url', $url)->first();
            return $tmp->name;
        } elseif ($url instanceof self) {
            return $url->name;
        } else {
            return null;
        }
    }
    
    ///

    static public function createNew(
        string $name, string $url, $img,
        string $title, $article, string $description,
        int $visible = 1, string $sticker = '',
        $group = -1, int $order = -1, bool $retObj = false
    ) {
        if (!Functions::testVar($gId = PageGroup::getFrom($group))) {
            $group_id = PageGroup::getRandId();
        } else {
            $group_id = $gId->id;
        }
        $tP = self::where(
            [
                ['url', '=', $url],
                ['name', '=', $name],
            ]
        )->get();
        if (!Functions::testVar($tP) || count($tP) === 0) {
            $tImg = Image::getImageToID($img);
            $tArt = Article::getToId($article);
            if (Functions::testVar($tImg) && Functions::testVar($tArt)) {
                $data = new self;
                $data->name = $name;
                $data->url = $url;
                $data->image_id = $tImg;
                $data->title = Functions::purifyContent($title);
                $data->article_id = $tArt;
                $data->description = Functions::purifyContent($description);
                //dd($visible);
                $data->viewable = $visible;
                $data->sticker = $sticker;

                //dd($data, $visible, "myFirst");

                /* 
                    // need to do some special checking on group_id..
                    if ($group_id < 0) {
                        $gID = self::max('group_id') + 1;
                    } else {
                        $tgo = self::where('group_id', $group_id)->get();
                    }
                    $data->group_id = $group_id;
                    $data->order = $order; 
                */
                if ($data->save()) {
                    //dd($data, 2);
                    if (PageGrouping::reorderAround($group_id, $data->id, $order)) {
                        //dd($data, 3);
                        $pg = PageGrouping::createNew($group_id, $data->id, $order);
                        $pi = PageImage::createNewFrom($data);
                        if (Functions::testVar($pg) && Functions::testVar($pi)) {
                            //dd($data, $pg, 4, "my");
                            return $retObj ? $data : $data->id;
                        }
                    }
                }
            }
        }
        return null;
    }

    public function updateWith(
        string $name, string $url, $img,
        string $title, $article, string $description,
        int $visible = 1, string $sticker = '',
        $group = -1, int $order = -1, bool $retObj = false
    ) {
        $whereBy = [
            ['id', '<>', $this->id]
        ];
        $orWhereBy = [
            ['id', '<>', $this->id]
        ];
        $tpg = $this->groups;
        $tg = PageGroup::getFrom($group, true);
        if (Functions::testVar($tpg) && Functions::countHas($tpg)) {
            if (Functions::testVar($tg) && PageGroup::isInListOf($tg, $tpg)) {
                $group_id = $tg->id;    
            } else {
                $group_id = PageGroup::getIdFrom($tpg[0], false, null);
            }
        } else {
            if (Functions::testVar($tg)) {
                $group_id = $tg->id;
            } else {
                $group_id = PageGroup::getRandId();
            }
        }
        $order_tmp = PageGrouping::getOrderForPage($this, $group, true);
        if ($order > 0 && Functions::testVar($order_tmp)) {
            if ($order !== $order_tmp) {
                $order_num = $order;
            } else {
                $order_num = $order_tmp;
            }
        } elseif ($order > 0  && !Functions::testVar($order_tmp)) {
            $order_num = $order;
        } elseif ($order < 1  && Functions::testVar($order_tmp)) {
            $order_num = $order_tmp;
        } else {
            $order_num = PageGrouping::getRandOrder($group_id, true);
        }
        if ($name !== $this->name) {
            $whereBy[] = ['name', '=', $name];
        } else {
            $whereBy[] = ['name', '=', $this->name];
        }
        if ($url !== $this->url) {
            $orWhereBy[] = ['url', '=', $url];
        } else {
            $orWhereBy[] = ['url', '=', $this->url];
        }
        $tP = self::withTrashed()->where($whereBy)->orWhere($orWhereBy)->get();
        if (!Functions::testVar($tP) || !Functions::countHas($tP)) {
            $tImg = Image::getImageToID($img);
            $tArt = Article::getToId($article);
            if (Functions::testVar($tImg) && Functions::testVar($tArt)) {
                $this->name = $name;
                $this->url = $url;
                $this->image_id = $tImg;
                $this->title = Functions::purifyContent($title);
                $this->article_id = $tArt;
                $this->description = Functions::purifyContent($description);
                //dd($visible);
                $this->viewable = $visible;
                $this->sticker = $sticker;

                if ($this->save()) {
                    //dd($data, 2);
                    if (PageGrouping::reorderAround($group_id, $this->id, $order_num)) {
                        //dd($data, 3);
                        $pg = PageGrouping::createNew($group_id, $this->id, $order_num);
                        $pi = PageImage::createNewFrom($this);
                        if (Functions::testVar($pg) && Functions::testVar($pi)) {
                            //dd($data, $pg, 4, "my");
                            return $retObj ? $this : $this->id;
                        }
                    }
                }
            }
        }
        return null;
    }

    static public function createNewFrom(
        array $array, bool $retObj = false
    ) {
        return self::createNew(
            $array['name'], $array['url'], $array['img'], 
            $array['title'], $array['article'], $array['description'], 
            $array['visible'], $array['sticker'], $array['group'], 
            $array['order'], $retObj
        );
    }

    /// 

    public function groups()
    {
        return $this->hasManyThrough(
            'App\PageGroup', 'App\PageGrouping',
            'page_id', 'id',
            'id', 'group_id'
        );
    } 

    public function images()
    {
        return $this->hasManyThrough(
            'App\Image', 'App\PageImage',
            'page_id', 'id',
            'id', 'image_id'
        );
    }

    public function image()
    {
        return $this->hasOne('App\Image', 'id', 'image_id');
    }

    public function article()
    {
        return $this->hasOne('App\Article', 'id', 'article_id');
    }

    public function getVisibility()
    {
        return $this->viewable;
    }

    ///

    static public function getOrderByKey()
    {
        return 'url';
    }

    static public function genUrlFragment(string $baseUrl, bool $fullUrl = false)
    {
        // {$tmp[0]}/section/{section}/category/{category}/product/{product}
        // $surl = $this->catalog->getFullUrl($baseUrl);
        //return $baseUrl . '/page/' . $this->url;
        $url = empty($baseUrl) || $baseUrl === 'store' 
            ? 'page/' 
            : $baseUrl . '/page/';
        return $fullUrl ? url($url) : $url;
    }

    public function toContentArrayOther(
        array $img = null, array $otherImages = null,
        array $links = null, bool $fullUrl = false
    ) {
        if (!Functions::testVar($img)) {
            $i = $this->image;
        } else {
            $i = $img;
        }
        if (!Functions::testVar($links)) {
            $b = self::genBreadcrumb('Home', $fullUrl ? url('/') : '/');
        } else {
            $b = $links;
        }
        if (!Functions::testVar($otherImages)) {
            //$o = PageImage::getAllImages($this->id, true);
            $o = Image::getArraysFor($this->images);
        } else {
            $o = $otherImages;
        }
        return self::makeContentArray(
            $this->article, $this->description, 
            $this->getFullUrl($baseUrl, $fullUrl),
            $this->name, $this->title, $i, $o, 
            self::getBreadcrumbs(
                self::genBreadcrumb(
                    $this->name, 
                    $this->getFullUrl($baseUrl, $fullUrl)
                ),
                $b
            ), 
            $this->getVisibility()
        );
    }

    public function toContentArrayPlus(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true, 
        bool $fullUrl = false, bool $useBaseMaker = true,
        string $dir = 'asc'
    ) {
        return self::makeContentArray(
            $this->article, $this->description,  
            $this->getFullUrl($baseUrl, $fullUrl), $this->name,
            $useTitle ? $this->title : $this->image->alt, 
            $this->image, Image::getArraysFor($this->images),
            self::getBreadcrumbs(
                self::genBreadcrumb(
                    $this->name, 
                    $this->getFullUrl($baseUrl, $fullUrl)
                ),
                self::genBreadcrumb('Home', $fullUrl ? url('/') : '/')
            ), 
            $this->getVisibility(), [
                'created' => $this->created_at,
                'modified' => $this->updated_at,
                'deleted' => $this->deleted_at
            ], $useBaseMaker
        );
    }

    /*  public function toTableArray(
            string $baseUrl = 'store', int $version = 1, 
            bool $useTitle = true, bool $withTrashed = true,
            bool $fullUrl = false
        ) {
            $url = $this->getFullUrl($baseUrl, $fullUrl);
            return self::makeTableArray(
                $this->name, $url, $useTitle ? $this->title : $this->image->alt,
                $this->image, $this->description,
                $this->sticker, $this->getDatesArray(),  $this->id,
                [], '', '', []
            );
        } 
    */

    public function numChildren(bool $withTrashed = true)
    {
        return 0;
    }

    public function getChildren(
        $transform = null, bool $withTrashed = true, 
        string $dir = 'asc', string $baseUrl = 'store',
        bool $useTitle = true, bool $fullUrl = false, 
        int $version = 1, $default = [], bool $useBaseMaker = true,
        bool $done = true
    ) {
        return [];
    }

    static public function getChildrenFor(
        $args, string $baseUrl = 'store', $transform = null, 
        bool $useTitle = true, int $version = 1, 
        bool $withTrashed = true, bool $fullUrl = false, 
        $default = [], bool $useBaseMaker = true,
        string $dir = 'asc'
    ) {
        return $default;
    }

    static public function getSelf(
        string $baseUrl = 'store', bool $withTrashed = true,
        bool $fullUrl = false, $children = [], 
        $paginator = null, string $pagingFor = ''
    ) {
        $title = $name = 'Content Pages';
        $article = [];
        $img = Image::createImageArray(
            'notebook-581128_640.jpg', 'Content Pages Listing', 
            'images/site', 'Content Pages Listing'
        );
        $pagingFor = $pagingFor ?: 'pagesPanel';
        return self::makeSelf(
            $name, $title, $article,
            $img, $baseUrl, $withTrashed,
            $fullUrl, $children, $paginator,
            $pagingFor, null
        );
    }

    static public function makeContentArray(
        $article, string $header, string $url,
        string $name, string $title, $img, 
        // string $description,
        array $otherImages = null,
        array $breadcrumbs = null, 
        int $visible = 0, array $dates = [], 
        bool $useBaseMaker = true
    ) {
        $i = Image::getImageArray($img);
        $a = Article::getArticle($article, true);
        $content = $useBaseMaker
            ? self::makeBaseContentArray(
                $name, $url, $i, $a, $title, $dates, 
                $otherImages, null, false, true, ''
            )
            : [
                'title' => $title,
                'name' => $name,
                'url' => $url,
                'content' => [
                    'header' => $header,
                    'article' => $a,
                    'img' => $i,
                ],
                'breadcrumbs' => $breadcrumbs,
                'visible' => $visible,
                'otherImages' => $otherImages,
                'dates' => $dates,
            ]; 
        if ($useBaseMaker) {
            $content['value']['header'] = $header;
            $content['value']['visible'] = $visible;
            $content['value']['breadcrumbs'] = $breadcrumbs;
        }
        return $content;
    }

    static public function getNamedPage(
        $url, $path = null, bool $getObj = false,
        bool $fullUrl = false, bool $useBaseMaker = true
    ) {
        $page = self::where('url', $url)->first();
        //dd($page, $url, __METHOD__);
        if (Functions::testVar($page)) {
            //  $ca = $page->toContentArray($image, $otherImages);
            if (!$getObj) {
                return $page->toContentArrayPlus(
                    Functions::isAdminPath($path) ? 'admin' : '', 
                    1, true, Functions::isAdminPath($path), 
                    $fullUrl, $useBaseMaker, 'asc'
                );
            } else {
                return $page;
            }
        } else {
            return null;
        }
    }

    static public function getOrderedByPageGroupings(
        string $dir = 'asc', $transform = null, 
        bool $useTitle = true, bool $fullUrl = false,
        string $baseUrl = '', bool $withTrashed = true,
        bool $useBaseMaker = true
    ) {
        $pages = [];
        $tpg = PageGroup::getOrdered($dir, $withTrashed, 'order');
        if (Functions::countHas($tpg)) {
            foreach ($tpg as $group) {
                $pg = $group->getChildren(
                    $transform, $withTrashed, $dir, $baseUrl,
                    $useTitle, $fullUrl, 1, [], $useBaseMaker,
                    self::getIfDoneIterating($transform)
                );
                foreach ($pg as $page) {
                    if (Functions::testVar($page)) {
                        $pages[] = $page;
                    }
                }
            }
        }
        return $pages;
    }

    /*  
        /// these two methods should overide the trait for Page's
        ///  more complicated Ordering.. 
        /// Should return a Collection. 


        static public function getOrderedBy(
            string $dir = 'asc', bool $withTrashed = true,
            $orderingBy = null
        ) {
            if (self::acceptableOrderingByKey($orderingBy)) {
                $key = $orderingBy;
            } elseif (is_callable($orderingBy)) {
                $key = $orderingBy();
                if (!self::acceptableOrderingByKey($key)) {
                    $key = self::getOrderByKey();    
                }
            } else {
                $key = self::getOrderByKey();
            }
            return $withTrashed 
                ? self::withTrashed()
                    ->orderBy($key, $dir)
                : self::orderBy($key, $dir);
        }

        static public function getOrdered(
            string $dir = 'asc', bool $withTrashed = true,
            $orderingBy = null
        ) {
            return self::getOrderedBy(
                $dir, $withTrashed, $orderingBy
            );
        }
    */

    static public function getAllPages(
        bool $asArrays = true, string $dir = 'asc',
        bool $usePageGroupings = true, string $path = '', 
        bool $fullUrl = false,
        string $pagingFor = '', int $viewNumber = 0, 
        int $pageNum = 0, int $numShown = 4,
        string $baseUrl = '',
        bool $useTitle = true,
        bool $withTrashed = false,
        bool $useBaseMaker = true
    ) {
        /* 
            $tmp = self::join('page_groups', 'pages.id', '=', 'page_groups.page')
            //->orderBy('page_groups.group', $order)
            ->select('pages.*', 'page_groups.page', 'page_groups.group', 'page_groups.order')
            ->groupBy('pages.id', 'page_groups.group')
            ->orderBy('page_groups.order', $order)
            ->get(); 
        */
        // TODO BASICLIST ITEM:
        // create a manual groupBy function
        // as PDO '@BARFED@' on the query above...
        // Optional: create a 'PageGroupInfo' 
        //  Table + Migration for use with PageGroup..
        /// UPDATE: DONE (on PageGroup) AND DONE (as PageGrouping)
        /// UPDATE: DONE ALL (including groupBy [getOrderedByPageGroupings()])
        if ($usePageGroupings) {
            $pages = self::getOrderedByPageGroupings(
                $dir, $asArrays, $useTitle, $fullUrl, $baseUrl,
                $withTrashed, $useBaseMaker
            );
            return self::getPaginatedItemsArray(
                $pages, $pageNum, $numShown, 
                $pagingFor, $path, 
                $viewNumber
            );
        } else {
            return self::getAllWithPagination(
                $asArrays, $pageNum, $numShown, $pagingFor, 
                $dir, $withTrashed, $baseUrl, $path, 
                $viewNumber, $fullUrl, $useTitle, 1, [],
                $useBaseMaker
            );
        }
    }
}
