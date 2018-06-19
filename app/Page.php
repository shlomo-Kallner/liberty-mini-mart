<?php

namespace App;

use Illuminate\Database\Eloquent\Model, 
    Illuminate\Http\Request,
    DB,
    Session;

class Page extends Model
{

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
     * 
     * @return array - a descriptor of a link.
     */
    static public function genLink(
        string $type, string $url, string $name, string $cssExtraClasses = '',
        string $icon = '', string $textTransform = '', string $target = '', 
        array $submenus = null, string $iconAfter = ''
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
        ];
    }

    static public function genURLMenuItem(
        string $url, string $name, string $icon = '', 
        string $textTransform = '', string $cssExtraClasses = '', 
        string $iconAfter = ''
    ) {
        // previously called 'genPreHeaderURL()'
        return static::genLink( 
            'url', $url, $name, $cssExtraClasses, 
            $icon, $textTransform, '', null, $iconAfter 
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
            $textTransform, $target, null, $iconAfter
        );
    }

    static public function genDropdownLink(
        string $name, array $submenus = [], string $icon = '', 
        string $textTransform = '', string $cssExtraClasses = '',
        string $url = 'javascript:void(0);', string $iconAfter = 'fa-angle-right'
    ) {
        return static::genLink( 
            'dropdown', $url, $name, $cssExtraClasses,
            $icon, $textTransform, '', $submenus, $iconAfter
        );
    }

    static public function getNavBar($genFakeData = false) 
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
        } else {
            // for pre-database testing:
            $navbar = [
                self::genURLMenuItem('about', 'About'),
                self::genURLMenuItem('store', 'Store'),
                //self::genURLMenuItem('store/section/test', 'TEST-SECTION'),
                //self::genURLMenuItem('store/section/test/category/test', 'TEST-CATEGORY'),
                self::genURLMenuItem('store/section/test/category/test/product/test', 'TEST-PRODUCT'),
                //self::genURLMenuItem('template', 'My Template'),
            ];
        }
        return $navbar;
    }

    static public function getPreHeader($genFakeData = false) 
    {
        //$testing = $genFakeData;
        $testing = true;
        $preheader = [];
        //dd(session()->all());
        $loggedin = session()->has('user') ? true : false;

        if (!$testing) {
            
        } else {
            if (!$loggedin) {
                // {{-- UPDATE: changing 'Log In' url to 'Sign In' url. --}}
                $preheader[] = self::genModalMenuItem('Sign In', '#login-modal', 'fa-sign-in', 'text-uppercase');
                $preheader[] = self::genURLMenuItem('signup', 'Sign up', 'fa-user', 'text-uppercase');
            } else {
                $preheader[] = self::genURLMenuItem('user', 'My Account', 'fa-id-card', 'text-uppercase');
                $preheader[] = self::genURLMenuItem('wishlist', 'My Wishlist', 'fa-calendar-o', 'text-uppercase');
                $preheader[] = self::genURLMenuItem('checkout', 'Checkout', 'fa-shopping-cart', 'text-uppercase');
                $preheader[] = self::genURLMenuItem('signout', 'Sign out', 'fa-sign-out', 'text-uppercase');
            }
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
        }
        return $res;
    }

    static public function getNameForURL($url)
    { // to be implemented with a db query!
        return $url === '' ? 'index' : '' ;
    }

    static public function getBreadcrumbs(Request $request, bool $genFakeData = false)
    {
        if (!$genFakeData) {

            $path = $request->segments();
            $res = [];
            for ($i = 0; $i < count($path); $i++) {
                $page = '';
                for ($j = 0; $j < $i; $j++) {
                    $page .= ( $j > 0 ? DIRECTORY_SEPARATOR . $path[$j] : $path[$j] );
                }
                $res['links'][] = self::genURLMenuItem(
                    $page, 
                    static::getNameForURL($page)
                );
            }
            $res['current'] = self::genURLMenuItem(
                $request->url(), 
                static::getNameForURL($request->url())
            );
        } else {
            $res = [
                'links' => [ 
                    static::genURLMenuItem('', ''), 
                    static::genURLMenuItem('', '')
                ],
                'current' => static::genURLMenuItem('', '')
            ];
        }
        return $res;
    }
    

}
