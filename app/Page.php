<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB,
    Session;

class Page extends Model {
    /*
     * A template for a menu item ..
     * 
     * $templateItem = [
      'icon' => '', // the Font Awesome 4 icon class without the 'fa'.
      'name' => '', // the name to fill in the Link.
      'url' => '', // the URL of the link.
      'isModal' => false, // a Boolean, Is this a Modal or a URL?
      'target' => '', // the data-target attribute's data value (of a modal)
      'transform' => '', // Bootstrap 3 text-transform css class.
      ];
     * 
     */

    static public function genURLMenuItem(string $url, string $name, string $icon = '', string $textTransform = '') {
        // previously called 'genPreHeaderURL()'
        $template = [
            'icon' => $icon, // the Font Awesome 4 icon class without the lone 'fa'.
            'name' => $name, // the name to fill in the Link.
            'url' => $url, // the URL of the link. 
            'isModal' => false, // a Boolean, Is this a Modal or a URL?
            'target' => '', // the data-target attribute's data value (of a modal)
            'transform' => $textTransform, // Bootstrap 3 text-transform css class.
        ];
        return $template;
    }

    static public function genModalMenuItem(string $name, string $target, string $icon = '', string $textTransform = '') {
        // previously called 'genPreHeaderModal()'
        $template = [
            'icon' => $icon, // the Font Awesome 4 icon class without the lone 'fa'.
            'name' => $name, // the name to fill in the Link.
            'url' => '#', // the URL of the link. 
            'isModal' => true, // a Boolean, Is this a Modal or a URL?
            'target' => $target, // the data-target attribute's data value (of a modal)
            'transform' => $textTransform, // Bootstrap 3 text-transform css class.
        ];
        return $template;
    }

    static public function getNavBar() {
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
            $navbar = DB::table('pages')::all()->toArray();
        } else {
            // for pre-database testing:
            $navbar = [
                self::genURLMenuItem('about', 'About'),
//                [
//                    'url' => 'about',
//                    'name' => 'About',
//                ],
                self::genURLMenuItem('store', 'Store'),
//                [
//                    'url' => 'store',
//                    'name' => 'Store',
//                ],
                self::genURLMenuItem('template', 'My Template'),
//                [
//                    'url' => 'template',
//                    'name' => 'My Template'
//                ],
            ];
        }
        return $navbar;
    }

    static public function getPreHeader() {
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

}
