<?php

namespace App;

use Illuminate\Database\Eloquent\Model, 
    Illuminate\Http\Request,
    App\Utilities\Functions\Functions,
    DB,
    App\Article,
    App\Section,
    App\Categorie,
    App\PageGroup,
    App\PageGrouping,
    App\User,
    Session;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];


    /** 
     * A template for a menu item ..
     * 
     * @param string $type  // 'url' for a url link, 
     *                      'modal' for a modal button link.. 
     *                      NEW! 'type' replaces 'isModal'!
     * @param string $url // the URL of the link. 
     * @param string $name // the name to fill in the Link.
     * @param string $cssExtraClasses, // extra CSS classes for the anchor tag...
     *                                 // (Bootstrap 3/other..)
     * @param string $icon // the Font Awesome 4 icon class without the '.fa' class.
     *                     // AKA fills in as so: '<i class="fa {{ $icon }}">'
     * @param string $textTransform // Bootstrap 3 text-transform css class.
     * @param string $target // the data-target attribute's data value of a modal,
     *                       // or a dropdown or tab...
     * @param array $submenus // submenu-links individually generated also by this
     *                        // method..  
     * @param string $iconAfter // 'the Font Awesome 4 icon class' inserted AFTER
     *                          // the NAME unlike $icon which PRECEDES the NAME.. 
     * @param string $toggle // the data-toggle attribute's parameter...
     * 
     * @param string $role // the role attribute's parameter...
     * 
     * @return array - a descriptor of a link.
     */
    static public function genLink(
        string $type, string $url, string $name, string $cssExtraClasses = '',
        string $icon = '', string $textTransform = '', string $target = '', 
        array $submenus = null, string $iconAfter = '', string $toggle = '',
        string $role = '', string $controls = ''
    ) {
        return [
            'type' => $type, // 'url' for a url link, 'modal' for a modal 
            // button link.. 
            //-@NEW! => 'type' replaces 'isModal'!
            'icon' => $icon, // the Font Awesome 4 icon class without the 
            // lone 'fa'.
            'name' => $name, // the name to fill in the Link.
            'url' => $url, // the URL of the link. 
            'target' => $target, // the data-target attribute's data value 
            // (of a modal)
            'transform' => $textTransform, // Bootstrap 3 text-transform css class.
            'submenu' => $submenus,
            'cssExtraClasses' => $cssExtraClasses, // extra CSS classes for the 
            // anchor tag... (Bootstrap 3/other..)
            'iconAfter' => $iconAfter, // 'the Font Awesome 4 icon class' inserted 
            // AFTER the NAME unlike $icon which PRECEDES the NAME.. 
            'toggle' => $toggle, // the data-toggle attribute's parameter...
            'role' => $role, // the role attribute's parameter...
            'controls' => $controls, // for collapse toggles..
        ];
    }

    static public function genURLMenuItem(
        string $url, string $name, string $icon = '', 
        string $textTransform = '', string $cssExtraClasses = '', 
        string $iconAfter = '', string $role = ''
    ) {
        // previously called 'genPreHeaderURL()'
        return static::genLink( 
            'url', $url, $name, $cssExtraClasses, 
            $icon, $textTransform, '', null, $iconAfter, '', $role 
        );
    }

    static public function genModalMenuItem(
        string $name, string $target, string $icon = '', 
        string $textTransform = '', string $cssExtraClasses = '', 
        string $iconAfter = ''
    ) {
        // previously called 'genPreHeaderModal()'
        return static::genLink( 
            'modal', '#', $name, $cssExtraClasses, $icon,
            $textTransform, $target, null, $iconAfter, 'modal', 'button'
        );
    }

    static public function genDropdownLink(
        string $name, array $submenus = [], string $icon = '', 
        string $textTransform = '', string $cssExtraClasses = '',
        string $url = 'javascript:void(0);', string $iconAfter = 'fa-angle-right'
    ) {
        return static::genLink( 
            'dropdown', $url, $name, "dropdown-toggle " . $cssExtraClasses,
            $icon, $textTransform, '', $submenus, $iconAfter, 'dropdown', 'button'
        );
    }

    /** 
     * For generating Bootstrap 3 Collapse.js Link tags.
     * 
     * @param string $id                - the link's "target" 
     *                                  (without any '#' as the method 
     *                                  will add it as appropriate)
     * @param string $name              - the link's content
     * @param string $cssExtraClasses
     * @param string $icon
     * @param string $textTransform
     * @param string $iconAfter
    */
    static public function genCollapseLink(
        string $id, string $name, string $cssExtraClasses = '',
        string $icon = '', string $textTransform = '', string $iconAfter = ''
    ) {
        return self::genLink(
            'collapse', '#' . $id, $name, $cssExtraClasses,
            $icon, $textTransform, '', null, $iconAfter, 'collapse',
            'button', $id
        );
    }

    static public function getNavBar($genFakeData = false, string $area = 'store') 
    {
        //$testing = $genFakeData;
        $testing = true;

        /*
         * // get the URLs of the defined content pages from 
          // the pages table
          // and then append to it the categories and sub-categories,
          // as per our intended layout
          // and return them as an Array.
          //
          // Example:
          //   """
          //      [
          //      'pages' => [
          //       'name' => 'url',
          //        ...
          //      ],
          //      'categories' => [
          //       'name' => ['url', ['sub-categories'] ],
          //       ...
          //      ]
          //      ]
          //   """
          //
         */

        if (!$testing) {
            //$navbar = DB::table('pages')::all()->toArray();
            $navbar = [];
            if ($area == 'store') {
                
            } else {
                $tmp = self::where('visible', '>',  0)
                    ->groupBy('group_id')
                    ->orderBy('order', 'asc')
                    ->get();
                dd($tmp);
            }
        } else {
            // for pre-database testing:
            $navbar = [
                self::genURLMenuItem('about', 'About'),
                self::genURLMenuItem('store', 'Store'),
                //self::genURLMenuItem('store/section/test', 'TEST-SECTION'),
                //self::genURLMenuItem('store/section/test/category/test', 'TEST-CATEGORY'),
                //self::genURLMenuItem('store/section/test/category/test/product/test', 'TEST-PRODUCT'),
                //self::genURLMenuItem('template', 'My Template'),
            ];
        }
        return $navbar;
    }

    static public function getPreHeader($genFakeData = false) 
    {
        $testing = $genFakeData; // no-op ... for now..
        //$testing = true;
        $preheader = [];

        //dd(session()->all());
        //$loggedin = session()->has('user') ? true : false;
        //$is_admin = session()->has('is_admin') ? true : false;

        $loggedin = User::getIsUser();
        $is_admin = User::getIsAdmin();


        if (!$loggedin) {
            // {{-- UPDATE: changing 'Log In' url to 'Sign In' url. --}}
            $preheader[] = self::genModalMenuItem('Sign In', '#login-modal', 'fa-sign-in', 'text-uppercase');
            $preheader[] = self::genURLMenuItem('signup', 'Sign up', 'fa-user', 'text-uppercase');
        } else {
            $preheader[] = self::genURLMenuItem('user', 'My Account', 'fa-id-card-o', 'text-uppercase');
            $preheader[] = self::genURLMenuItem('wishlist', 'My Wishlist', 'fa-calendar-o', 'text-uppercase');
            $preheader[] = self::genURLMenuItem('checkout', 'Checkout', 'fa-shopping-cart', 'text-uppercase');
            if ($is_admin) {
                $preheader[] = self::genURLMenuItem('admin', 'Dashboard', 'fa-bar-chart', 'text-uppercase');
            }
            $preheader[] = self::genURLMenuItem('signout', 'Sign out', 'fa-sign-out', 'text-uppercase');
        }
        return $preheader;
    }
    
    static public function getSidebar($genFakeData = false)
    {
        $res = [];
        if ($genFakeData) {
            $res[] = self::genURLMenuItem('lib/themewagon/metronicShopUI/theme/shop-product-list.html', 'Ladies');
            
            $tmp1 = [];
            $tmp1[] = self::genDropdownLink('Classic', [
                self::genURLMenuItem('lib/themewagon/metronicShopUI/theme/shop-product-list.html', 'Classic 1'),
                self::genURLMenuItem('lib/themewagon/metronicShopUI/theme/shop-product-list.html', 'Classic 2')
                ]
            );
            $tmp1[] = self::genDropdownLink('Sport', [
                self::genURLMenuItem('lib/themewagon/metronicShopUI/theme/shop-product-list.html', 'Sport 1'),
                self::genURLMenuItem('lib/themewagon/metronicShopUI/theme/shop-product-list.html', 'Sport 2')
                ]
            );

            $tmp = [];
            $tmp[] = self::genDropdownLink('Shoes', $tmp1);

            $tmp[] = self::genURLMenuItem('lib/themewagon/metronicShopUI/theme/shop-product-list.html', 'Trainers');
            $tmp[] = self::genURLMenuItem('lib/themewagon/metronicShopUI/theme/shop-product-list.html', 'Jeans');
            $tmp[] = self::genURLMenuItem('lib/themewagon/metronicShopUI/theme/shop-product-list.html', 'Chinos');
            $tmp[] = self::genURLMenuItem('lib/themewagon/metronicShopUI/theme/shop-product-list.html', 'T-Shirts');

            $res[] = self::genDropdownLink('Mens', $tmp);
            
            $res[] = self::genURLMenuItem('lib/themewagon/metronicShopUI/theme/shop-product-list.html', 'Kids');
            $res[] = self::genURLMenuItem('lib/themewagon/metronicShopUI/theme/shop-product-list.html', 'Accessories');
            $res[] = self::genURLMenuItem('lib/themewagon/metronicShopUI/theme/shop-product-list.html', 'Sports');
            $res[] = self::genURLMenuItem('lib/themewagon/metronicShopUI/theme/shop-product-list.html', 'Brands');
            $res[] = self::genURLMenuItem('lib/themewagon/metronicShopUI/theme/shop-product-list.html', 'Electronics');
            $res[] = self::genURLMenuItem('lib/themewagon/metronicShopUI/theme/shop-product-list.html', 'Home &amp; Garden');
            $res[] = self::genURLMenuItem('lib/themewagon/metronicShopUI/theme/shop-product-list.html', 'Custom Link');
        } else {
            $sections = Section::all();
            //dd($sections);
            foreach ($sections as $section) {
                // each section is a dropdown containing categories
                // each category is a url link.
                $cats = Categorie::where('section_id', $section->id)->get();
                //dd($section, $cats);
                $subs = [];
                foreach ($cats as $cat) {
                    $subs[] = self::genURLMenuItem(
                        "store/section/". $section->url . "/category/". $cat->url, 
                        $cat->title
                    );
                }
                //dd($subs);
                $res[] = self::genDropdownLink($section->title, $subs);
                //dd($res);
            }
        }
        return $res;
    }

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

    static public function genBreadcrumb(string $name = '', string $url = '')
    {
        return static::genURLMenuItem($url, $name);
    }

    static public function getBreadcrumbs(array $current = null, array $links = null)
    {
        $res = [
                'links' => $links ?? static::genBreadcrumb(),
                'current' => $current ?? static::genBreadcrumb()
            ];
        return $res;
    }

    static public function genBreadcrumbsFromPath(string $url = '')
    {
        $links = [];
        if (str_contains($url,'/')) {
            $paths = explode('/', $url);
            if ($paths !== false) {
                for ($i = 0; $i < count($paths); $i++) {
                    $path = $paths[$i];
                    $links[] = $path;

                }
            }
        }
        return $links;
    }

    public function toContentArray(
        array $img = null, array $otherImages = null,
        array $links = null
    ) {
        if (!Functions::testVar($img)) {
            $i = Image::getImageArray($this->image);
        } else {
            $i = $img;
        }
        if (!Functions::testVar($links)) {
            $b = self::genBreadcrumb('Home', '/');
        } else {
            $b = $links;
        }
        if (!Functions::testVar($otherImages)) {
            $o = PageImage::getAllImages($this->id, true);
        } else {
            $o = $otherImages;
        }
        return self::makeContentArray(
            $this->article, $this->description, $this->title,
            $i, $o, 
            self::getBreadcrumbs(
                self::genBreadcrumb($this->name, $this->url),
                $b
            ), 
            $this->visible
        );
        /*     
            return [
                'title' => $this->title,
                'content' => [
                    'header' => $this->title,
                    'article' => [
                        'header' => $this->description,
                        'subheading' => $i['cap'],
                        'img' => $i['img'],
                        'imgAlt' => $i['alt'],
                        'article' => $this->article
                    ]
                ],
                'breadcrumbs' => self::getBreadcrumbs(
                    self::genBreadcrumb($this->name, $this->url),
                    $b
                ),
                'visible' => $this->visible,
                'otherImages' => $o,
            ];  
        */
    }

    static public function makeContentArray(
        $article, string $header, 
        string $title, $img, 
        // string $description,
        array $otherImages = null,
        array $breadcrumbs = null, 
        int $visible = 0
    ) {
        $i = Image::getImageArray($img);
        /**
         * [
                    'header' => $description,
                    'subheading' => $i['cap'],
                    'img' => $i['img'],
                    'imgAlt' => $i['alt'],
                    'article' => $article
                ]
         */
        $a = Article::getArticle($article);
        return [
            'title' => $title,
            'content' => [
                'header' => $header,
                'article' => $a,
                'img' => $i,
            ],
            'breadcrumbs' => $breadcrumbs,
            'visible' => $visible,
            'otherImages' => $otherImages,

        ]; 
    }

    static public function getNamedPage($url, $path = null, bool $getObj = false)
    {
        $page = self::where('url', $url)->first();
        //dd($page, $url, __METHOD__);
        if (Functions::testVar($page)) {
            $image = Image::getImageArray($page->image);
            $otherImages = [];
            $oit = PageImage::getAllImages($page->id);
            if (Functions::testVar($oit)) {
                foreach ($oit as $i) {
                    $t = Image::getImageArray($i);
                    if (Functions::testVar($t)) {
                        $otherImages[] = $t;
                    }
                }
            }
            //  $ca = $page->toContentArray($image, $otherImages);
            if (!$getObj) {
                return $page->toContentArray($image, $otherImages);
            } else {
                return [
                    'page' => $page,
                    'image' => $image,
                    'otherImages' => $otherImages
                ];
            }
        } else {
            //abort(404);
            return [];
        }
    }

    /* public function groups()
    {
        return $this->hasMany('App\PageGroup', 'page');
    } */

    public function image()
    {
        return $this->hasOne('App\Image', 'id', 'image_id');
    }

    public function article()
    {
        return $this->hasOne('App\Article', 'id', 'article_id');
    }

    static public function getAllPages(bool $getObj = false, $order = 'asc')
    {
        /* $tmp = self::join('page_groups', 'pages.id', '=', 'page_groups.page')
            //->orderBy('page_groups.group', $order)
            ->select('pages.*', 'page_groups.page', 'page_groups.group', 'page_groups.order')
            ->groupBy('pages.id', 'page_groups.group')
            ->orderBy('page_groups.order', $order)
            ->get(); */
        $tmp = self::all();
        $pages = [];
        if (Functions::testVar($tmp)) {
            dd($tmp);
            // TODO BASICLIST ITEM:
            // create a manual groupBy function
            // as PDO '@BARFED@' on the query above...
            // Optional: create a 'PageGroupInfo' 
            //  Table + Migration for use with PageGroup..
            foreach ($tmp as $page) {
                $g = $page->groups
                    ->orderBy('group', 'order')
                    ->get();
                dd($g);
                //$pages[] = '';
            }
            //dd($pages);
        }
        return $pages;
    }
    
    /**
     * Function genPagination() - Generate Pagination Information as acceptable by
     *                            'lib.themewagon.paginator'...
     *  All Numbers passed are indexes starting from zero, 
     *  although they are displayed by the component with one added to them..
     *
     * @param integer $pageNum  - the current page number
     * @param integer $firstItemShownOnPage - the index of the first item being shown
     * @param integer $lastItemShownOnPage - the index of the last item being shown
     * @param integer $totalItems - the total number of items that can be paged through
     * @param array $rangeOfAllItemIndexes - an array created by Functions::genRange()
     *                                       of all the indexes of all the items..
     * @return array 
     */
    static public function genPagination(
        int $pageNum, int $firstItemShownOnPage, int $lastItemShownOnPage,
        int $totalItems, array $rangeOfAllItemIndexes, int $numPagesPerPagingView = 4,
        string $pagingFor = ''
    ) {
        return [
            'currentRange' => [
                'index' => $pageNum,
                'begin' => $firstItemShownOnPage,
                'end' => $lastItemShownOnPage,
            ],
            'totalItems' => $totalItems,
            'ranges' => $rangeOfAllItemIndexes,
            'numPerView' => $numPagesPerPagingView,
            'pagingFor' => $pagingFor
        ];
    }

    static public function createNew(
        string $name, string $url, $img,
        string $title, $article, string $description,
        int $visible = 1, string $sticker = '',
        $group_id = -1, int $order = -1
    ) {
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
                $data->visible = $visible;
                $data->sticker = $sticker;

                if (!Functions::testVar($gId = PageGrouping::getFrom($group_id))) {
                    $gId = PageGrouping::getRandId();
                }
                //if ()

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
                    if (PageGroup::reorderAround($group_id, $data->id, $order)) {
                        if (Functions::testVar(PageGroup::createNew($group_id, $data->id, $order))) {
                            return $data->id;
                        }
                    }
                }
            }
        }
        return null;
    }

    static public function createNewFrom(array $array) 
    {
        return self::createNew(
            $array['name'], $array['url'], $array['img'], 
            $array['title'], $array['article'], $array['description'], 
            $array['visible'], $array['sticker'], $array['group_id'], 
            $array['order']
        );
    }

    static public function getFromId(int $id)
    {
        return self::where('id', $id)->first();
    }

    static public function existsId(int $id)
    {
        return Functions::testVar(self::getFromId($id));
    }
}
