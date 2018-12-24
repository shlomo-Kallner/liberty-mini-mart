<?php

namespace App\Utilities;

use App\Utilities\Functions\Functions,
    Illuminate\Support\Collection,
    Illuminate\Http\Request,
    App\PageGroup,
    App\PageGrouping,
    App\User,
    App\Section;

trait LinkGenerator {

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
            'submenus' => $submenus,
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
        return self::genLink( 
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
        return self::genLink( 
            'modal', '#', $name, $cssExtraClasses, $icon,
            $textTransform, $target, null, $iconAfter, 'modal', 'button'
        );
    }

    static public function genDropdownLink(
        string $name, array $submenus = [], string $icon = '', 
        string $textTransform = '', string $cssExtraClasses = '',
        string $url = 'javascript:void(0);', string $iconAfter = 'fa-angle-right'
    ) {
        return self::genLink( 
            'dropdown', $url, $name, 
            "dropdown-toggle " . $cssExtraClasses,
            $icon, $textTransform, '', 
            $submenus, $iconAfter, 'dropdown', 
            'button', ''
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

    static public function getNavBar(
        bool $genFakeData = false, string $area = 'store', 
        string $defDropName = 'All Pages', int $numPerView = 6
    ) {
        $testing = $genFakeData;
        //$testing = true;

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
        **/

        $navbar = [];
        if (true) {
            //$navbar = DB::table('pages')::all()->toArray();
            $tg = PageGroup::getAllGroups(false);
            if (Functions::testVar($tg) && count($tg) > 0) {
                $t1 = [];
                foreach ($tg as $g) {
                    $t2 = [];
                    $tpg = PageGrouping::getGroup($g);
                    $numPg = Functions::genRowsPerPage(count($tpg), $numPerView);
                    if ($numPg > 1) {
                        for ($i = 1; $i <= $numPg; $i++) {
                            $tpg1 = $tpg->forPage($i, $numPerView);
                            $t3 = [];
                            foreach ($tpg1 as $pg1) {
                                $tp1 = $pg1->page;
                                $t3[] = self::genURLMenuItem(
                                    'page/' . $tp1->url, $tp1->name
                                );
                            }
                            $t2[] = self::genDropdownLink(
                                $g->name . ' Part ' . $i, $t3
                            );
                        }
                    } else {
                        foreach ($tpg as $pg1) {
                            $tp1 = $pg1->page;
                            $t2[] = self::genURLMenuItem(
                                'page/' . $tp1->url, $tp1->name
                            );
                        }
                    }
                    $t1[] = self::genDropdownLink($g->name, $t2);
                }
                if (count($t1) === 1) {
                    $navbar[] = $t1[0];
                } elseif (count($t1) > 1) {
                    $navbar[] = self::genDropdownLink($defDropName, $t1);
                }
            }
            $navbar[] = self::genURLMenuItem('store', 'Store');
        } else {
            // for pre-database testing:
            $navbar[] = self::genURLMenuItem('about', 'About');
            $navbar[] = self::genURLMenuItem('store', 'Store');
            if (false) {
                $navbar[] = self::genURLMenuItem('store/section/test', 'TEST-SECTION');
                $navbar[] = self::genURLMenuItem('store/section/test/category/test', 'TEST-CATEGORY');
                $navbar[] = self::genURLMenuItem('store/section/test/category/test/product/test', 'TEST-PRODUCT');
            }
            if (false) {
                $navbar[] = self::genURLMenuItem('template', 'My Template');
            }
        }
        return $navbar;
    }

    static public function getPreHeader(bool $genFakeData = false) 
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
    
    static public function getSidebar(bool $genFakeData = false)
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
                $cats = $section->categories;
                //dd($section, $cats);
                $subs = [];
                //$section_url = "store/section/". $section->url;
                foreach ($cats as $cat) {
                    // $section_url . "/category/". $cat->url
                    $subs[] = self::genURLMenuItem(
                        $cat->getFullUrl('store'), 
                        $cat->title
                    );
                }
                //dd($subs);
                $res[] = self::genDropdownLink(
                    $section->title, $subs, '', '', '',
                    $section->getFullUrl('store')
                );
                //dd($res);
            }
        }
        return $res;
    }

    static public function genBreadcrumb(
        string $name = '', string $url = ''
    ) {
        return static::genURLMenuItem($url, $name);
    }

    static public function getBreadcrumbs(
        array $current = null, array $links = null
    ) {
        $res = [
            'links' => $links ?? [ static::genBreadcrumb() ],
            'current' => $current ?? static::genBreadcrumb()
        ];
        return $res;
    }

    static private function genBreadcrumbsFromPath(string $url = '')
    {
        $links = [];
        if (str_contains($url, '/')) {
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
    
}