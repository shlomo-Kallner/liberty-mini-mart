<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use HTMLPurifier;

class MainController extends Controller {

    static public $data = [
        'title' => '',
        'siteName' => '',
        'nav' => [],
        'page' => [
            'header' => '',
            'article' => '',
        //'name' => '',
        ],
    ];
    protected $site = [
        'name' => 'Liberty MiniMart',
        'titleNameSep' => ' | ',
    ];

    public function __construct($name = '', $titleNameSep = '') {
        $this->setSiteName($name, $titleNameSep);
    }

    /// Begin Utility Functions

    public function setSiteName($name = '', $titleNameSep = '') {
        $this->site['name'] = !empty($name) ? $name : $this->site['name'];
        self::$data['siteName'] = $this->site['name'];
        $this->site['titleNameSep'] = !empty($titleNameSep) ? $titleNameSep : $this->site['titleNameSep'];
    }

    public function setTitle($title = '') {
        if (!empty($title)) {
            self::$data['title'] = $this->site['name'];
            self::$data['title'] .= $this->site['titleNameSep'];
            self::$data['title'] .= $title;
        }
    }

    public function setPageContent($content, string $val = '') {
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

    public function getView(string $viewName = 'content.template', string $title = '', array $content = []) {
        $this->setTitle($title);
        $this->setPageContent($content);
        return view($viewName, self::$data);
    }

    public function getTemplateView(string $title = '', array $content = []) {
        return getView('content.template', $title, $content);
    }

    /// End Utility Functions
    //  
    /// Begin Test Views Functions

    public function test(Request $request) {
        self::$data['requestedPage'] = !empty($request->page) ? $request->page : 'index';
        $this->setTitle('test ' . self::$data['requestedPage'] . ' page');
        //dd($request);
        return view('content.test', self::$data);
    }

    public function test1(Request $request) {
        $requestedPage = !empty($request->page) ? $request->page : 'index';
        $this->setTitle('test ' . $requestedPage . ' page');
        //dd($request);
        self::$data['page']['name'] = $requestedPage;
        $this->setPageContent('article', "<p><i>HEllloo WORLD!!</i></p>");
        return view('content.test1', self::$data);
    }

    public function test2(Request $request) {
        $requestedPage = !empty($request->page) ? $request->page : 'index';
        $title = 'test ' . $requestedPage . ' page';
        $content = [
            'header' => "<b>$requestedPage</b>",
            'article' => "<p><i>HEllloo WORLD!!</i></p>"
        ];
        return $this->getTemplateView($title, $content);
    }

}
