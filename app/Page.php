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

}
