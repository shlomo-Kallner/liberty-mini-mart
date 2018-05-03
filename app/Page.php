<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB,
    Session;

class Page extends Model {

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
                [
                    'url' => 'about',
                    'name' => 'About',
                ],
                [
                    'url' => 'store',
                    'name' => 'Store',
                ],
                [
                    'url' => 'template',
                    'name' => 'My Template'
                ],
            ];
        }
        return $navbar;
    }

    /*
     * A template for a preheader/topbar menu item stuff..
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

    static public function genPreHeaderURL(string $url, string $name, string $icon = '', string $textTransform = '') {
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

    static public function genPreHeaderModal(string $name, string $target, string $icon = '', string $textTransform = '') {
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

    static public function getPreHeader() {
        $testing = true;
        $preheader = [];
        //dd(session()->all());
        $loggedin = session()->has('user') ? true : false;

        if (!$testing) {
            
        } else {
            if (!$loggedin) {
                // {{-- UPDATE: changing 'Log In' url to 'Sign In' url. --}}
                $preheader[] = self::genPreHeaderModal('Sign In', '#login-modal', 'fa-sign-in', 'text-uppercase');
                $preheader[] = self::genPreHeaderURL('signup', 'Sign up', 'fa-user', 'text-uppercase');
            } else {
                $preheader[] = self::genPreHeaderURL('user', 'My Account', 'fa-id-card', 'text-uppercase');
                $preheader[] = self::genPreHeaderURL('wishlist', 'My Wishlist', 'fa-calendar-o', 'text-uppercase');
                $preheader[] = self::genPreHeaderURL('checkout', 'Checkout', 'fa-shopping-cart', 'text-uppercase');
                $preheader[] = self::genPreHeaderURL('signout', 'Sign out', 'fa-sign-out', 'text-uppercase');
            }
        }
        return $preheader;
    }

}
