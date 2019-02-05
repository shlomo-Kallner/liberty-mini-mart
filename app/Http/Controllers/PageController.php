<?php

namespace App\Http\Controllers;

use App\Page,
    App\Product,
    App\Article,
    App\UserSession,
    App\Image;
use Illuminate\Http\Request,
    App\Utilities\Functions\Functions,
    Illuminate\Support\Facades\Log,
    Illuminate\Support\Facades\Validator,
    Illuminate\Support\Str,
    Illuminate\Validation\Rule;
    

class PageController extends MainController 
{
    
    public function __construct($name = '', $titleNameSep = ' | ') 
    {
        parent::__construct($name, $titleNameSep);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) 
    {
        $pagesData = [
            'PageNum' => 0,
            'NumShown' => 12,
            'PagingFor' => 'pagesPanel',
            'Dir' => 'asc',
            'WithTrashed' => Functions::isAdminPath($request->path()),
            'BaseUrl' => Functions::isAdminPath($request->path()) ? 'admin' : '',
            'ViewNum' => 0,
            'UseBaseMaker' => $request->ajax(),
            'Default' => [],
            'UseTitle' => true,
            'FullUrl' => !$request->ajax(),
            'ListUrl' => $request->path(),
            'UsePageGroupings' => false,
            'UseGetSelf' => false,
            'Transform' => Page::TO_TABLE_ARRAY_TRANSFORM
        ];
        $usePagination = false;
        if ($usePagination) {
            $pv = Page::getPagingVars(
                $request, $pagesData['PagingFor'], $pagesData['NumShown'],
                $pagesData['Dir']
            );
            if (Functions::testVar($pv)) {
                $pagesData['PageNum'] = $pv['pageNum'];
                $pagesData['ViewNum'] = $pv['viewNum'];
                if (Functions::hasPropKeyIn($pv, 'limit')) {
                    $pagesData['NumShown'] = $pv['limit'];
                }
            } 
            if ($pagesData['UsePageGroupings']) {
                $pages = Page::getAllPages(
                    true, $pagesData['Dir'], $pagesData['UsePageGroupings'], 
                    $pagesData['ListUrl'], $pagesData['FullUrl'],
                    $pagesData['PagingFor'], $pagesData['ViewNum'], 
                    $pagesData['PageNum'], $pagesData['NumShown'], 
                    $pagesData['BaseUrl'], $pagesData['UseTitle'], 
                    $pagesData['WithTrashed'], $pagesData['UseBaseMaker']
                );
            } else {
                $pages = Page::getAllWithPagination(
                    $pagesData['Transform'], $pagesData['PageNum'], 
                    $pagesData['NumShown'], $pagesData['PagingFor'], 
                    $pagesData['Dir'], $pagesData['WithTrashed'], 
                    $pagesData['BaseUrl'], $pagesData['ListUrl'], 
                    $pagesData['ViewNum'], $pagesData['FullUrl'], 
                    $pagesData['UseTitle'], 1, $pagesData['Default'], 
                    $pagesData['UseBaseMaker']
                );
                if ($pagesData['UseGetSelf']) {
                    $children = Functions::countHas($pages) 
                        ? $pages['items'] : null;
                    $paginator = Functions::countHas($pages) 
                        ? $pages['pagination'] : null;
                    $pages_index = Page::getSelf(
                        $pagesData['BaseUrl'], $pagesData['WithTrashed'],
                        $pagesData['FullUrl'], $children, 
                        $paginator, $pagesData['PagingFor']
                    );
                }
            }
        } else {
            $pages = [];
            $pages['items'] = Page::getAllWithTransform(
                $pagesData['Transform'], $pagesData['Dir'], 
                $pagesData['WithTrashed'], $pagesData['BaseUrl'], 
                $pagesData['UseTitle'], $pagesData['FullUrl'], 
                1, $pagesData['Default'], $pagesData['UseBaseMaker']
            );
        }
        //dd($pages);
        if ($request->ajax()) {
            return $pages;
        } else {
            $title = 'Our Content Pages';
            $bcLinks = [];
            $bcLinks[] = self::getHomeBreadcumb();
            if (Functions::isAdminPath($request->path())) {
                $bcLinks[] = CmsController::getAdminBreadcrumb();
            }
            $breadcrumbs = Page::getBreadcrumbs(
                Page::genBreadcrumb(
                    $title, 
                    Page::genUrlFragment($pagesData['BaseUrl'], $pagesData['FullUrl'])
                ),
                $bcLinks
            );
            return self::getView(
                $request, 'cms.items_table', $title, $pages, false, $breadcrumbs, null,
                Functions::isAdminPath($request->path()) 
                ? CmsController::getAdminSidebar() : null
            );
        }
        UserSession::updateAndAbort($request, 404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) 
    {
        $BaseUrl = Functions::isAdminPath($request->path()) ? 'admin' : '';
        $content = [
            'hasName' => 'true',
            'hasTitle' => 'true',
            'hasUrl' => 'true',
            'hasDescription' => 'true',
            'hasArticle' => 'true',
            'hasImage' => 'true',
            'hasSticker' => 'true',
            'thisURL' => Page::genUrlFragment($BaseUrl, !$request->ajax()),
        ];
        $bcLinks = [];
        $bcLinks[] = self::getHomeBreadcumb();
        if (Functions::isAdminPath($request->path())) {
            $bcLinks[] = CmsController::getAdminBreadcrumb();
        }
        $breadcrumbs = Page::getBreadcrumbs(
            Page::genBreadcrumb(
                'Content Page Creation Form', 
                Page::genUrlFragment($BaseUrl, !$request->ajax())
            ),
            $bcLinks
        );
        return self::getView(
            $request, 'cms.forms.new.page', 'Create a New Content Page',
            $content, false, $breadcrumbs, null, 
            Functions::isAdminPath($request->path()) ? CmsController::getAdminSidebar()
            : null
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {
        //dd((new Page)->getTable());
        $validator = Validator::make(
            $request->all(), self::creationValidationRules(), 
            self::creationValidationMessages()
        );
        if ($validator->passes()) {
            if ($request->hasFile('image')) {
                
                $image_id = Image::storeAndCreateNewImage(
                    $request->file('image'), 'pages', 
                    $request->title, $request->description, 
                    false, 0
                );
            } else {
                $image_id = 0;
            }
            // Log::info("image_id: " . $image_id);
            $article_id = Article::createNew(
                $request->article, $request->title, 
                null, $request->subheading??'', false
            );
            $visible = 1;
            $group = -1; 
            $order = -1;
            $page = Page::createNew(
                $request->name, $request->url, $image_id,
                $request->title, $article_id, $request->description,
                $visible, $request->sticker,
                $group, $order, true
            );
            if (Functions::testVar($page)) {
                self::addMsg('Page ' . $page->name . ' , ' . $page->title . ' Creation Successfully!');
                UserSession::updateRegenerate(
                    $request, intval(User::getIdFromUserArray(false))
                );
                //$request->session()->regenerate();
                return redirect('admin/page');
            }
            self::addMsg("Uhhh, if we got here then PRODUCT CREATION FAILED!!!");
            self::addMsg("You probably chose a cu");
            //dd($page);
        }
        $path = $request->path();
        $path = Str::contains($path, 'create') ? $path : $path . '/create';
        // return redirect($path)
        //     ->withErrors($validator)
        //     ->withInput($request->except('image'));
        if (!$request->ajax()) {
            return UserSession::updateRedirect(
                $request, $path, $validator, 
                $request->except('image')
            );
        } else {
            abort(404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //public function show(Request $request, $page) 
    public function show(Request $request) 
    {
        //$page_info = Page::getNamedPage($page, $request->path());
        $page_info = Page::getNamedPage(
            $request->page, $request->path(), false, 
            Functions::isAdminPath($request->path()),
            true
        );
        //dd($request, $page_info);
        $visible = $page_info['value']['visible'] > 0 || Functions::isAdminPath($request->path());
        if (Functions::testVar($page_info) && $visible) {
            // WISHLIST ITEM: a more sophisticated user role based
            //  Visibility / Access Control Method..
            return self::getView(
                $request, 'content.content', $page_info['value']['title'], 
                $page_info['content'] ?? $page_info['value'],
                false, $page_info['value']['breadcrumbs']
            );
        } else {
            
            if (!$request->ajax()) {
                UserSession::updateAndAbort($request, 404);
            } else {
                abort(404);
            }
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request) 
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $page) 
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Page  $pages
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $pages) 
    {
        //
    }

    public function showDelete(Request $request) 
    {
        $page = Page::getNamedPage($request->page, $request->path, true);
        if (Functions::testVar($page)) {
            return self::getView(
                $request, 'cms.forms.delete.page', 
                'Are You Sure That You Want To Delete This Page?',
                ['data' => $page],
                false,
                Page::getBreadcrumbs(
                    Page::genBreadcrumb('Delete Page', $request->path()),
                    Page::genBreadcrumb('Admin Dashboard', 'admin')
                ) 
            );
        } else {
            abort(404);
        }
    }

    public function home(Request $request) 
    {
        //dd('hello');
        // for now...
        if (true) {
            //dd('hello');
        
            //$title = 'test ' . $requestedPage . ' page';
            $article = Article::makeArticleArray(
                e("<p> World War I-era poster depicts colonial-era celebratory crowd in front of Independence Hall in Philadelphia, PA. Large Liberty Bell used as decorative element. Published by Sackett & Wilhelms Corp, N.Y., ca. 1917- ca. 1919 </p>"),
                e("<b>Home</b>"), (2),
                e("<p>Ring It Again/Buy U.S. Gov&apos;t Bonds/Third Liberty Loan</p>"),
                true
            );
            Log::info('info 1' . __METHOD__);
            $newProducts = Product::getNewProducts(4, 'New Arrivals');
            Log::info('info 2' . __METHOD__);
            $sampleProducts = Product::getNewProducts(3, 'Three Sample Items');
            Log::info('info 3' . __METHOD__);
            $bestsellers = Product::getBestsellers();
            Log::info('info 4' . __METHOD__);
            $content = [
                'article'=> $article,
                'newProducts' => $newProducts,
                'sampleProducts' => $sampleProducts,
                'bestsellers' => $bestsellers,
                'filters' => [], // search filters are a WISH-LIST ITEM!!!
                'pricings' => [], // membership plan pricings are a WISH-LIST ITEM!!
            ];
            Log::info('info 5' . __METHOD__);
            //sdd('hello', 3, $content);
        
            // $useFakeData = false;
            //self::$data['sidebar'] = Page::getSidebar($useFakeData);
            //dd($request->path());
            $breadcrumbs = Page::getBreadcrumbs(
                Page::genBreadcrumb('Home', '/')
            );
            Log::info('info 6' . __METHOD__);
            //return $this->getTemplateView($title, $content);
            //dd($request->session()->all());
            //dd(session()->all());
            return self::getView(
                $request, 'content.index', 'Home', 
                $content, false, $breadcrumbs
            );
        } else {
            return $this->test3($request);
        }

    }

    //// Validation Rules and messages

    /**
     * Get the validation rules that apply to the creation request.
     *
     * @return array
     */
    static public function creationValidationRules()
    {
        return [
            //
            'image' => 'required|file|image',
            'article' => 'required|max:255000|string|min:3',
            'subheading' => 'string|nullable|max:255',
            'title' => 'required|max:255|string|min:3',
            'name' => 'required|max:255|string|min:3',
            'description' => 'required|max:255|string|min:3',
            'url' => [
                'required', 'max:255', 'string', 'min:3',
                'regex:'. Functions::getURLRegexStr(),
                Rule::exists((new Page)->getTable())->where(function ($query, $value, $closure) {
                    $query->where('url', '<>', $value);
                }),
            ],
            'sticker' => [
                'string', 'nullable', 'regex:/new|sale/'
            ],
        ];
    }

    /**
     * Get the validation rules that apply to the modification request.
     *
     * @return array
     */
    static public function modificationValidationRules()
    {
        return [
            'image' => 'nullable|file|image',
            'article' => 'required|max:255000|string|min:3',
            'subheading' => 'string|nullable|max:255',
            'title' => 'required|max:255|string|min:3',
            'name' => 'required|max:255|string|min:3',
            'description' => 'required|max:255|string|min:3',
            'url' => [
                'required', 'max:255', 'string', 'min:3',
                'regex:'. Functions::getURLRegexStr(),
            ],
            'sticker' => [
                'string', 'nullable', 'regex:/new|sale/'
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    static public function creationValidationMessages()
    {
        return [
            'title.required' => 'A title is required',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    static public function modificationValidationMessages()
    {
        return [
            'title.required' => 'A title is required',
        ];
    }

    /// UTILITY METHODS:
    /// Testing Functions Start:

    public function index1(Request $request) 
    {
        $requestedPage = !empty($request->page) ? $request->page : 'index';
        $title = 'test ' . $requestedPage . ' page';
        $content = [
            'header' => "<b>$requestedPage</b>",
            'article' => [
                'header' => "<p><i>HEllloo WORLD!!</i></p>"
                ]
        ];

        return self::getView($request, 'content.tests.index2', $title, $content);
    }

    public function test(Request $request) 
    {
        $requestedPage = !empty($request->page) ? $request->page : 'index';
        $title = 'test ' . $requestedPage . ' page';
        $content = [
            'header' => "<b>$requestedPage</b>",
            'article' => Article::makeContentArray(
                "<p>" . e('World War I-era poster depicts colonial-era celebratory crowd in front of Independence Hall in Philadelphia, PA. Large Liberty Bell used as decorative element. Published by Sackett & Wilhelms Corp, N.Y., ca. 1917- ca. 1919') . "</p>",
                '', 2, "<p>'Ring It Again/Buy U.S. Gov&apos;t Bonds/Third Liberty Loan'</p>"
            ),
            'breadcrumbs' => ''
        ];

        //return $this->getTemplateView($title, $content);
        //dd($request->session()->all());
        //dd(session()->all());
        self::$data['new_products'] = [
            'products' => [],
            'currency' => 'fa-usd',
        ];
        self::$data['pricing'] = [];
        self::$data['sidebar'] = [
            'sidebar' => [],
            'filters' => [
                'name' => '', // <= this the title/heading text of the filter!
                'filter' => '', // <= this is the HTML of the filter!
            ],
            'bestsellers' => [
                [
                    'url' => '',
                    'img' => '',
                    'alt' => '',
                ],
            ],
        ];
        return self::getView($request, 'content.tests.test2', $title, $content);
    }

}
