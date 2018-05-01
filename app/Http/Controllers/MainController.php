<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use HTMLPurifier,
    \App\Page;

class MainController extends Controller {

    static public $data = [
        'title' => '', // 'tab' title
        'siteName' => '', // still here for compatibility..
        'site' => [
            /// UPDATE: Moving the $site variable's data into
            ///         $data so Blade will to be able to
            ///         access it directly..
            'name' => 'Liberty MiniMart',
            'titleNameSep' => ' | ',
        ],
        'navbar' => [], // the header navbar data...
        'sidebar' => [], // the side[navigation]bar data...
        'footer' => [], // the footer navigation bar data..
        /// UPDATE: Replaced 'Preheader' with 'footer'
        ///         as - currently - the preheader bar's 
        ///         content is pre-determined, and not for 
        ///         CMS editing..
        'page' => [
            'header' => '', // 'h1' page article content
            'article' => '', // 'div' page article content
        //'name' => '',
        ],
        'user' => [
            'loggedin' => false,
            'name' => '',
            'email' => ''
        ],
    ];

    /* OLD CODE... kept in case of problems..
     * 
      protected $site = [
      'name' => 'Liberty MiniMart',
      'titleNameSep' => ' | ',
      ];
     */

    public function __construct($name = '', $titleNameSep = ' | ') {
        self::setSiteName($name, $titleNameSep);
        self::$data['navbar'] = Page::getNavBar();
    }

    /// Begin Utility Functions
    /// UPDATE: converting all Utility functions to static functions...

    static public function setSiteName($name = '', $titleNameSep = ' | ') {
        self::$data['site']['name'] = !empty($name) ?
                $name :
                self::$data['site']['name'];
        self::$data['siteName'] = self::$data['site']['name'];
        self::$data['site']['titleNameSep'] = !empty($titleNameSep) ?
                $titleNameSep :
                self::$data['site']['titleNameSep'];
    }

    /*
     * a helper function for setting members of the 'site'
     * member of the static data variable...
     * without necessarily overwriting presets...
     * 
     */

    static public function setSiteData($content, $val = null) {
        if (!empty($content)) {
            if (is_string($content)) {
                if (!empty($val)) {
                    self::$data['site'][$content] = $val;
                }
            } elseif (is_array($content)) {
                foreach ($content as $key => $value) {
                    self::$data['site'][$key] = $value;
                }
            }
        }
    }

    static public function setTitle($title = '') {
        if (!empty($title)) {
            self::$data['title'] = self::$data['site']['name'];
            self::$data['title'] .= self::$data['site']['titleNameSep'];
            self::$data['title'] .= $title;
        }
    }

    static public function setPageContent($content, string $val = '') {
        if (!empty($content)) {
            $purifier = new HTMLPurifier();
            if (is_string($content)) {
                if (!empty($val)) {
                    self::$data['page'][$content] = $purifier->purify($val);
                }
            } elseif (is_array($content)) {
                foreach ($content as $key => $value) {
                    self::$data['page'][$key] = $purifier->purify($value);
                }
            }
        }
    }

    static public function getView(string $viewName = 'content.template', string $title = '', array $content = []) {
        self::setTitle($title);
        self::setPageContent($content);
        return view($viewName, self::$data);
    }

    static public function getTemplateView(string $title = '', array $content = []) {
        return self::getView('content.template', $title, $content);
    }

    /// End Utility Functions
    //  
    /// Begin Test Views Functions

    public function test(Request $request) {
        self::$data['requestedPage'] = !empty($request->page) ? $request->page : 'index';
        self::setTitle('test ' . self::$data['requestedPage'] . ' page');
        //dd($request);
        return view('content.tests.test', self::$data);
    }

    public function test1(Request $request) {
        $requestedPage = !empty($request->page) ? $request->page : 'index';
        self::setTitle('test ' . $requestedPage . ' page');
        //dd($request);
        self::$data['page']['name'] = $requestedPage;
        self::setPageContent('article', "<p><i>HEllloo WORLD!!</i></p>");
        return view('content.tests.test1', self::$data);
    }

    public function test2(Request $request) {
        $requestedPage = !empty($request->page) ? $request->page : 'index';
        $title = 'test ' . $requestedPage . ' page';
        $content = [
            'header' => "<b>$requestedPage</b>",
            'article' => "<p><i>HEllloo WORLD!!</i></p>"
        ];
        //return $this->getTemplateView($title, $content);
        return self::getView('content.tests.test2', $title, $content);
    }

}
