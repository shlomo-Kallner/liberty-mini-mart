<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request,
    Illuminate\Support\HtmlString,
    App\Page,
    App\User,
    App\Cart,
    App\Article,
    App\Utilities\Functions\Functions,
    Session;

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
            'usingCDNs' => '',
            'usingMix' => '',
            'scripts' => [
                /* Example:
                    'script-name' => [
                        'local-path' => '',
                        'cdn-url' => '',
                        'mix-path' => '',
                        'version' => ''
                    ]
                */
            ],
        ],
        'preheader' => [], // the preheader navbar data...
        'navbar' => [], // the header navbar data...
        'sidebar' => [], // the side[navigation]bar data...
        'footer' => [], // the footer navigation bar data.. THIS MAY REMAIN STATIC!
        'breadcrumbs' => [
            // my old placeholder schema..
            'links' => [
                [
                    'name' => '',
                    'url' => '',
                ],
            ],
            'current'=> [
                'name' => '',
                'url' => '',
            ],
        ],
        /// UPDATE: Replaced 'Preheader' with 'footer'
        ///         as - currently - the preheader bar's 
        ///         content is pre-determined, and not for 
        ///         CMS editing..
        /// Further Update: 'preheader' restored (above)
        ///                 as the preheader bar is now a  
        ///                 dynamically generated component
        ///                 and Blade Foreach using code block.
        'page' => [
            'header' => '', // page 'h1' content header..
            'article' => [
                'header' => '', // 'h2' page article content
                'subheading' => '', // 'h3' page article content
                'article' => '', // 'div' page article content
                'img' => '', // page article img array/id content
                // 'imgAlt' => '', // page img alt content // old img alt..
            ],
        ],
        'user' => [
            'name' => '',
            'email' => '',
            'id' => '',
            'agent' => '',
            'ip' => '',
        ],
        'cart' => [
            'items' => null,
            'currency-icon' => 'fa-usd',
            'sub-total' => 0,
            'total-items' => 0,
        ],
        'alert' => [
            'class' => '',
            'title' => '',
            'content' => '',
            'timeout' => '',
        ],
    ];

    /* OLD CODE... kept in case of problems..
     * 
      protected $site = [
      'name' => 'Liberty MiniMart',
      'titleNameSep' => ' | ',
      ];
     */

    public function __construct($name = '', $titleNameSep = ' | ') 
    {
        self::setSiteName($name, $titleNameSep);
        //        self::$data['navbar'] = Page::getNavBar();
        //        //dd(session()->all());
        //        self::$data['preheader'] = Page::getPreHeader();
        //        //dd(self::$data['preheader']);
        //        // a proposal for using the UserController or the User model..
        //        self::$data['user'] = User::getUser();
        //        self::$data['cart'] = User::getCart();
    }

    /// Begin Utility Functions
    /// UPDATE: converting all Utility functions to static functions...

    static public function addMsg(string $msg)
    {
        if (session()->has('msgs')) {
            session()->push('msgs', $msg);
        } else {
            $tmp = [];
            $tmp[] = $msg;
            session()->put('msgs', $msg);
        }
    }

    static public function getMsgs()
    {
        if (session()->has('msgs')) {
            $msgs = session()->get('msgs');
            if (Functions::testVar($msgs)) {
                return $msgs;
            }
        } else {
            return null;
        }
    }

    static public function setAlert(
        $css = '', $title = '', $content = '', int $timeout = 0, string $id = ''
    ) {
        self::$data['alert'] = [
            'class' => $css,
            'title' => Functions::purifyContent($title),
            'content' => Functions::purifyContent($content),
            'timeout' => $timeout,
            'id' => $id
        ];
    }

    static public function setSiteName($name = '', $titleNameSep = ' | ') 
    {
        self::$data['site']['name'] = !empty($name) ?
                $name :
                self::$data['site']['name'];
        self::$data['siteName'] = & self::$data['site']['name'];
        self::$data['site']['titleNameSep'] = !empty($titleNameSep) ?
                $titleNameSep :
                self::$data['site']['titleNameSep'];
    }

    /**
     * A helper function for setting members of the 'site'
     * member of the static data variable...
     * without necessarily overwriting presets...
     * 
     */
    static public function setSiteData($content, $val = null) 
    {
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

    // WISHLIST TO-DO ITEM!!! 
    static public function addSiteScript(
        string $script, string $local = '', string $cdn = '',
        string $mix = '', string $version = ''
    ) {
        if (! self::hasSiteScript($script)) {
            self::$data['site']['scripts'][$script] = [
                    'local-path' => $local,
                    'cdn-url' => $cdn,
                    'mix-path' => $mix,
                    'version' => $version
            ];
        } else {
            if (empty(self::$data['site']['scripts'][$script]['local-path']) && !empty($local)) {
                self::$data['site']['scripts'][$script]['local-path'] = $local;
            }
            if (empty(self::$data['site']['scripts'][$script]['cdn-url']) && !empty($cdn)) {
                self::$data['site']['scripts'][$script]['cdn-url'] = $cdn;
            }
            if (empty(self::$data['site']['scripts'][$script]['mix-path']) && !empty($mix)) {
                self::$data['site']['scripts'][$script]['mix-path'] = $mix;
            }
            if (empty(self::$data['site']['scripts'][$script]['version']) && !empty($version)) {
                self::$data['site']['scripts'][$script]['version'] = $version;
            }
        }
    }

    // WISHLIST TO-DO ITEM!!!
    static public function hasSiteScript($script = '')
    {
        $res = false;
        if (!empty($script)) {
            if (is_array($script)) {
                if (array_key_exists($script['name'], self::$data['site']['scripts'])) {
                    $data = self::$data['site']['scripts'][$script['name']];
                    if (array_key_exists('version', $script)) {
                        if (is_array($script['version'])) {
                            $res = Functions::testVersions(
                                $script['version']['version'], 
                                $data['version'], 
                                $script['version']['op']
                            );
                        } elseif (is_string($script['version'])) {
                            $res = Functions::testVersions(
                                $script['version'], 
                                $data['version']
                            );
                        }
                    } elseif (array_key_exists('cdn-url', $script)) {
                        $res = empty($data['cdn-url']);
                    } elseif (array_key_exists('mix-path', $script)) {
                        $res = empty($data['mix-path']);
                    } elseif (array_key_exists('local-path', $script)) {
                        $res = empty($data['local-path']);
                    }
                }
            } elseif (is_string($script)) {
                if (array_key_exists($script, self::$data['site']['scripts'])) {
                    $res = true;
                }
            }
        }
        return $res; // WISHLIST TO-DO ITEM!!!
    }

    static public function setTitle($title = '') 
    {
        if (!empty($title)) {
            self::$data['title'] = self::$data['site']['name'];
            self::$data['title'] .= self::$data['site']['titleNameSep'];
            self::$data['title'] .= Functions::purifyContent($title);
        }
    }

    static public function setPageContent($content, string $val = '') 
    {
        if (!empty($content)) {
            //$purifier = new HTMLPurifier();
            if (is_string($content) || $content instanceof HtmlString ) {
                // if $content is a key..
                if (!empty($val)) {
                    // $val is the value thereof..
                    self::$data['page'][$content] = Functions::purifyContent($val);
                }
            } elseif (is_array($content) || is_object($content)) {
                foreach ($content as $key => $value) {
                    self::$data['page'][$key] = Functions::purifyContent($value);
                }
            }
        }
    }

    static public function getView(
        string $viewName = 'content.content', string $title = '', $content = [], 
        bool $useFakeData = false, array $breadcrumbs = null, array $alert = null,
        array $sidebar = null
    ) {
        self::setTitle($title);
        self::setPageContent($content);
        if ($alert !== null || count($alert??[]) > 0) {
            self::setAlert(
                $alert['class'], $alert['title'], $alert['content'], 
                $alert['timeout'], $alert['id']
            );
        } elseif (self::$data['alert']['class'] === '') {
            $mgs = self::getMsgs();
            if (Functions::testVar($mgs)) {
                $tStr = '<ul>';
                foreach ($mgs as $msg) {
                    $tStr .= '<li>' . Functions::purifyContent($msg) . '</li>';
                }
                $tStr .= '</ul>';
                self::setAlert('alert-info', 'Here are Your Messages:', $tStr, 0);
            } else {
                self::setAlert();
            }
        }

        self::$data['navbar'] = Page::getNavBar($useFakeData);
        //dd(session()->all());
        self::$data['preheader'] = Page::getPreHeader($useFakeData);
        //dd(session()->all());
        //dd(self::$data['preheader']); 
        self::$data['sidebar'] = $sidebar ?? Page::getSidebar($useFakeData);
        //
        self::$data['breadcrumbs'] = $breadcrumbs ?? Page::getBreadcrumbs();
        //
        self::$data['user'] = User::getUserArray();
        //
        self::$data['cart'] = Cart::getCurrentCart();

        session()->regenerate();

        return view($viewName, self::$data);
    }

    static public function getTemplateView(
        string $title = '', $content = [], 
        bool $useFakeData = false, array $breadcrumbs = null, array $alert = null
    ) {
        return self::getView(
            'content.content', $title, $content, $useFakeData, $breadcrumbs, $alert
        );
    }

    static public function getLoremIpsum()
    {
        return str_replace(
            ["\r\n", "\r", "\n"], ' ', e(
                'Lorem ipsum dolor sit amet, 
        consectetur adipisicing elit. Modi, nulla, 
        porro facilis officiis sequi natus eum nemo 
        totam eius deserunt reprehenderit ducimus quia et 
        itaque animi nostrum adipisci accusantium. 
        Quaerat, eos ipsum expedita totam dolorem rem 
        reiciendis voluptatibus quia dolor quam natus 
        id ipsam aliquam fugiat ullam quibusdam unde 
        corporis minima debitis odit laborum numquam 
        repellat illo ea aut mollitia alias? Ut, facere, 
        inventore, mollitia consectetur cum repellat quidem 
        qui itaque modi quam laudantium cupiditate a nemo officia 
        deserunt laboriosam temporibus unde voluptate suscipit labore 
        voluptates cumque quas natus non in maiores dicta delectus omnis 
        aut commodi animi molestiae amet fugit? Tenetur, eligendi, 
        a pariatur laboriosam aliquid cum voluptate nisi 
        laudantium officiis in voluptatum nihil libero consequatur 
        tempora sunt dolorum beatae dicta quod illo impedit!'
            )
        );
    }

    /// End Utility Functions
    //  
    /// Begin Test Views Functions

    public function test(Request $request) 
    {
        self::$data['requestedPage'] = !empty($request->page) ? $request->page : 'index';
        self::setTitle('test ' . self::$data['requestedPage'] . ' page');
        //dd($request);
        return view('content.tests.test', self::$data);
    }

    public function test1(Request $request) 
    {
        $requestedPage = !empty($request->page) ? $request->page : 'index';
        self::setTitle('test ' . $requestedPage . ' page');
        //dd($request);
        self::$data['page']['name'] = $requestedPage;
        self::setPageContent('article', "<p><i>HEllloo WORLD!!</i></p>");
        return view('content.tests.test1', self::$data);
    }

    public function test2(Request $request) 
    {
        $requestedPage = !empty($request->page) ? $request->page : 'index';
        $title = 'test ' . $requestedPage . ' page';
        $content = [
            'article' => [
                'header' => e("<b>$requestedPage</b>"),
                'subheading' => e("<p>Ring It Again/Buy U.S. Gov&apos;t Bonds/Third Liberty Loan</p>"),
                'article'=> e("<p> World War I-era poster depicts colonial-era celebratory crowd in front of Independence Hall in Philadelphia, PA. Large Liberty Bell used as decorative element. Published by Sackett & Wilhelms Corp, N.Y., ca. 1917- ca. 1919 </p>"),
                'img' => "images/site/ring_it_liberty_bell.jpg",
                'imgAlt' => e('Ring It Again/Buy U.S. Gov&apos;t Bonds/Third Liberty Loan'),
            ]
        ];
        //self::$data['sidebar'] = Page::getSidebar($request, $useFakeData);
        //self::$data['breadcrumbs'] = Page::getBreadcrumbs($request, $useFakeData);

        //return $this->getTemplateView($title, $content);
        //dd($request->session()->all());
        //dd(session()->all());
        return self::getView('content.tests.test2', $title, $content);
    }

    public function test3(Request $request, array $alert = null) 
    {
        $requestedPage = !empty($request->page) ? $request->page : 'index';
        $title = 'test ' . $requestedPage . ' page';
        $content = [
            'article'=> Article::makeContentArray(
                e("<p> World War I-era poster depicts colonial-era celebratory crowd in front of Independence Hall in Philadelphia, PA. Large Liberty Bell used as decorative element. Published by Sackett & Wilhelms Corp, N.Y., ca. 1917- ca. 1919 </p>"),
                e("<b>$requestedPage</b>"), (2),
                e("<p>Ring It Again/Buy U.S. Gov&apos;t Bonds/Third Liberty Loan</p>"),
                true
            ),
        ];
        $useFakeData = true;
        //self::$data['sidebar'] = Page::getSidebar($useFakeData);
        //dd($request->path());
        $breadcrumbs = Page::getBreadcrumbs(
            Page::genBreadcrumb($requestedPage, $request->path())
        );

        //return $this->getTemplateView($title, $content);
        //dd($request->session()->all());
        //dd(session()->all());
        return self::getView('content.index', $title, $content, $useFakeData, $breadcrumbs, $alert);
    }

    public function test4(Request $request)
    {
        $alert = [
            'class' => 'alert-success',
            'title' => "HI! I&apos;m the Alert for the $request->page page!",
            'content' => self::getLoremIpsum(),
            'timeout' => 100000,
            'id' => ''
        ];
        return $this->test3($request, $alert);
    }

}
