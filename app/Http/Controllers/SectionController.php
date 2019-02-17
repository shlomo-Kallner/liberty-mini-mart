<?php

namespace App\Http\Controllers;

use App\Section,
    App\Page,
    App\Product,
    App\Categorie,
    App\Image,
    App\Article,
    App\UserSession,
    App\Utilities\Functions\Functions;
use Illuminate\Http\Request, 
    Illuminate\Validation\Rule,
    Illuminate\Support\Facades\Validator,
    Illuminate\Support\Str;

class SectionController extends MainController
{
    
    public function __construct($name = '', $titleNameSep = ' | ') 
    {
        parent::__construct($name, $titleNameSep);
    }

    /**
     * Display a listing of the resource.
     * 
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $is_admin = Functions::isAdminPath($request->path());
        $usePagination = !$is_admin; // false;
        $sectData = [
            'PageNum' => 0,
            'NumShown' => 6,
            'PagingFor' => 'sectionPanel',
            'Dir' => 'asc',
            'WithTrashed' => $is_admin,
            'BaseUrl' => $is_admin ? 'admin/store' : 'store',
            'ViewNum' => 0,
            'UseBaseMaker' => $request->ajax(),
            'Default' => [],
            'Version' => 1,
            'UseTitle' => true,
            'FullUrl' => !$request->ajax(),
            'ListUrl' => $request->path(),
            'UseGetSelf' => false,
            'Transform' => $usePagination 
                ? Section::TO_MINI_TRANSFORM
                : Section::TO_TABLE_ARRAY_TRANSFORM 
        ];
        $content = [];
        if ($usePagination) {
            $pv = Section::getPagingVars(
                $request, $sectData['PagingFor'], $sectData['NumShown'], $sectData['Dir']
            );
            if (Functions::testVar($pv)) {
                $sectData['PageNum'] = $pv['pageNum'];
                $sectData['ViewNum'] = $pv['viewNum'];
                if (Functions::hasPropKeyIn($pv, 'limit')) {
                    $sectData['NumShown'] = $pv['limit'];
                }
            } 
            $sections = Section::getAllWithPagination(
                $sectData['Transform'], $sectData['PageNum'], 
                $sectData['NumShown'], $sectData['PagingFor'], 
                $sectData['Dir'], $sectData['WithTrashed'], 
                $sectData['BaseUrl'], $sectData['ListUrl'], 
                $sectData['ViewNum'], $sectData['FullUrl'], 
                $sectData['UseTitle'], $sectData['Version'], 
                $sectData['Default'], $sectData['UseBaseMaker']
            );
            if ($sectData['UseGetSelf']) {
                $children = Functions::countHas($sections) 
                    ? $sections['items'] : null;
                $paginator = Functions::countHas($sections) 
                    ? $sections['pagination'] : null;
                $content = Section::getSelf(
                    $sectData['BaseUrl'], $sectData['WithTrashed'],
                    $sectData['FullUrl'], $children, 
                    $paginator, $sectData['PagingFor']
                );
            } else {
                $content = $sections;
            }
        } else {
            $content['items'] = Section::getAllWithTransform(
                $sectData['Transform'], $sectData['Dir'], 
                $sectData['WithTrashed'], $sectData['BaseUrl'], 
                $sectData['UseTitle'], $sectData['FullUrl'], 
                $sectData['Version'], $sectData['Default'], 
                $sectData['UseBaseMaker']
            );
        }
        if ($request->ajax()) {
            return $content;
        } else {
            // get a listing of all sections... 
            $title = 'All Our Sections';
            $bcLinks = [];
            $bcLinks[] = self::getHomeBreadcumb();
            if (!$is_admin) {
                $content['bestsellers'] = Product::getBestsellers();
                $viewName = 'content.items_list';
                $content['component'] = 'lib.themewagon.product_mini';
                $bcLinks[] = ShopController::getStoreBreadcrumbs($request);
            } else {
                $bcLinks[] = CmsController::getAdminBreadcrumb();
                $viewName = 'cms.items_table';
            }
            $breadcumbs = Page::getBreadcrumbs(
                Page::genBreadcrumb(
                    $title,
                    Section::genUrlFragment($sectData['BaseUrl'], $sectData['FullUrl'])
                ),
                $bcLinks
            );
            return self::getView(
                $request, $viewName, $title, 
                $content, false, $breadcumbs, null,
                $is_admin 
                ? CmsController::getAdminSidebar() : null
            );
        }
    }

    /**
     * Display a "Name Listing" of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        return Section::getNameListing();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $is_admin = Functions::isAdminPath($request->path());
        $usePagination = !$is_admin; // false;
        $sectData = [
            'PageNum' => 0,
            'NumShown' => 6,
            'PagingFor' => 'sectionPanel',
            'Dir' => 'asc',
            'WithTrashed' => $is_admin,
            'BaseUrl' => $is_admin ? 'admin/store' : 'store',
            'ViewNum' => 0,
            'UseBaseMaker' => $request->ajax(),
            'Default' => [],
            'Version' => 1,
            'UseTitle' => true,
            'FullUrl' => !$request->ajax(),
            'ListUrl' => $request->path(),
            'UseGetSelf' => false,
            'Transform' => $usePagination 
                ? Section::TO_MINI_TRANSFORM
                : Section::TO_TABLE_ARRAY_TRANSFORM 
        ];
        $content = [
            'hasName' => 'true',
            'hasTitle' => 'true',
            'hasUrl' => 'true',
            'hasDescription' => 'true',
            'hasArticle' => 'true',
            'hasImage' => 'true',
            'thisURL' => Section::genUrlFragment($sectData['BaseUrl'], $sectData['FullUrl']),
            'cancelUrl' => Section::genUrlFragment($sectData['BaseUrl'], $sectData['FullUrl']),
        ];
        
        $bcLinks = [];
        $bcLinks[] = self::getHomeBreadcumb();
        if (Functions::isAdminPath($request->path())) {
            $bcLinks[] = CmsController::getAdminBreadcrumb();
        }
        $bcLinks[] = Page::genBreadcrumb(
            'Our Produce Sections', 
            Section::genUrlFragment($sectData['BaseUrl'], $sectData['FullUrl'])
        );
        $breadcrumbs = Page::getBreadcrumbs(
            Page::genBreadcrumb(
                'Section Creation Form', 
                Section::genUrlFragment($sectData['BaseUrl'], $sectData['FullUrl'])
            ),
            $bcLinks
        );
        return self::getView(
            $request, 'cms.forms.new.section', 'Create a New Store Section',
            $content, false, $breadcrumbs, null, 
            Functions::isAdminPath($request->path()) ? CmsController::getAdminSidebar()
            : null
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $is_admin = Functions::isAdminPath($request->path());
        $validator = Validator::make(
            $request->all(), self::creationValidationRules(), 
            self::creationValidationMessages()
        );
        $path = $request->path();
        $passes = $validator->passes();
        if ($passes) {
            if ($request->hasFile('image')) {
                        
                $image_id = Image::storeAndCreateNewImage(
                    $request->file('image'), 'products', 
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
            // Log::info("article_id: " . $article_id);
            $catalog_id = 1;
            $section = Section::createNew(
                $request->name, $request->url, $request->title, 
                $article_id, $request->description, $image_id, 
                $catalog_id, true
            );
            if (Functions::testVar($section)) {
                self::addMsg('Section ' . $section->name . ' , ' . $section->title . ' Creation Successfully!');
                $path = 'admin/store/section';
            } else {
                Log::info("Uhhh, if we got here then PRODUCT CREATION FAILED!!!");
                $passes = null;
            }
        }
        $path = $passes || Str::contains($path, 'create') ? $path : $path . '/create';
        return UserSession::updateRedirect(
            $request, $path, $validator, 
            $request->except('image')
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        if (Functions::testVar($request->section)) {
            
            //dd($request->section, 'in show()');
            $is_admin = Functions::isAdminPath($request->path());
            $usePagination = !$is_admin;
            $tmpData = [
                'PageNum' => 0,
                'NumShown' => 12,
                'PagingFor' => 'sectionPanel',
                'Dir' => 'asc',
                'WithTrashed' => $is_admin,
                'BaseUrl' => $is_admin ? 'admin/store' : 'store',
                'ViewNum' => 0,
                'UseBaseMaker' => $request->ajax(),
                'Default' => [],
                'Version' => 1,
                'UseTitle' => true,
                'FullUrl' => !$request->ajax(),
                'ListUrl' => $request->path(),
                'UsePagination' => $usePagination,
                'Transform' => $usePagination 
                    ? Categorie::TO_MINI_TRANSFORM
                    : Categorie::TO_TABLE_ARRAY_TRANSFORM,
            ];   
            $tmpData['BaseUrl'] = $request->ajax() ? 'api/' . $tmpData['BaseUrl'] : $tmpData['BaseUrl'];
            $section = Section::getNamed($request->section);
            if (Functions::testVar($section)) {
                /*
                    $section_items = Categorie::getCategoriesOfSection($section->id);
                    $section_items = $section->getCategories(false);
                    getAllWithTransform(
                        $transform = null, string $dir = 'asc', 
                        bool $withTrashed = true, string $baseUrl = 'store', 
                        bool $useTitle = true, bool $fullUrl = false, 
                        int $version = 1, $default = [], 
                        bool $useBaseMaker = true
                    )
                    getAllWithPagination(
                        $transform, int $pageNum, int $numShown = 4, 
                        string $pagingFor = '', string $dir = 'asc', 
                        bool $withTrashed = true, string $baseUrl = 'store', 
                        string $listUrl = '', int $viewNumber = 0, 
                        bool $fullUrl = false, bool $useTitle = true, 
                        int $version = 1, $default = [], bool $useBaseMaker = true
                    )
                    getFor(
                        $args, string $baseUrl = 'store', $transform = null, 
                        bool $useTitle = true, int $version = 1, 
                        bool $withTrashed = true, bool $fullUrl = false, 
                        $default = [], bool $useBaseMaker = true,
                        string $dir = 'asc'
                    )
                    getForWithPagination(
                        $args, $transform, int $pageNum,
                        int $numShown = 4, string $pagingFor = '', 
                        string $listUrl = '', string $baseUrl = 'store', 
                        string $dir = 'asc', int $viewNumber = 0, 
                        bool $withTrashed = true, bool $useTitle = true, 
                        bool $fullUrl = false, int $version = 1, $default = [], 
                        bool $useBaseMaker = true
                    )
                    toContentArrayWithPagination(
                        string $baseUrl = 'store', int $version = 1, 
                        bool $useTitle = true, bool $withTrashed = true,
                        bool $fullUrl = false, int $pageNum = 0, 
                        int $numItemsPerPage = 4, string $pagingFor = '', 
                        int $viewNumber = 0, string $listUrl = '#', 
                        bool $useBaseMaker = true
                    )
                */
                //dd($section);
                if ($tmpData['UsePagination']) {
                    $pa = Section::getPagingVars(
                        $request, $tmpData['PagingFor'], $tmpData['NumShown'],
                        $tmpData['Dir']
                    );
                    if (Functions::testVar($pa)) {
                        $tmpData['ViewNum'] = $pa['viewNum'];
                        $tmpData['PageNum'] = $pa['pageNum'];
                        $tmpData['NumShown'] = $pa['limit'];
                        $tmpData['Dir'] = $pa['order'];
                    }
                    $section_data = Categorie::getForWithPagination(
                        $section->categories, $tmpData['Transform'], 
                        $tmpData['PageNum'], $tmpData['NumShown'], 
                        $tmpData['PagingFor'], $tmpData['ListUrl'], 
                        $tmpData['BaseUrl'], $tmpData['Dir'], 
                        $tmpData['ViewNum'], $tmpData['WithTrashed'], 
                        $tmpData['UseTitle'], $tmpData['FullUrl'], 
                        $tmpData['Version'], $tmpData['Default'],
                        $tmpData['UseBaseMaker']
                    );
                } else {
                    //dd($section->categories);
                    $section_data = [
                        'items' => Categorie::getFor(
                            $section->categories, $tmpData['BaseUrl'], 
                            $tmpData['Transform'], $tmpData['UseTitle'], 
                            $tmpData['Version'], $tmpData['WithTrashed'], 
                            $tmpData['FullUrl'], $tmpData['Default'],
                            $tmpData['UseBaseMaker'], $tmpData['Dir']
                        ),
                    ];
                    //dd($section_data);
                }
                if ($request->ajax()) {
                    return $section_data;
                }
                $bcLinks = [];
                $bcLinks[] = self::getHomeBreadcumb();
                if ($is_admin) {
                    $bcLinks[] = CmsController::getAdminBreadcrumb();
                    $viewName = 'cms.items_table';
                } else {
                    $bcLinks[] = ShopController::getStoreBreadcrumbs($request);
                    $section_data[] = Product::getBestsellers();
                    $viewName = 'content.items_list';
                    $section_data['component'] = 'lib.themewagon.product_mini';
                }
                $bcLinks[] = Page::genBreadcrumb(
                    'Our Produce Sections', 
                    Section::genUrlFragment($tmpData['BaseUrl'], $tmpData['FullUrl'])
                );
                $breadcumbs = Page::getBreadcrumbs(
                    Page::genBreadcrumb(
                        $section->name, 
                        $section->getFullUrl($tmpData['BaseUrl'], $tmpData['FullUrl'])
                    ),
                    $bcLinks
                );
                return self::getView(
                    $request, $viewName, $section->title, $section_data, 
                    false, $breadcumbs, null, 
                    Functions::isAdminPath($request->path()) ? CmsController::getAdminSidebar()
                    : null
                );
            }
        } else {
            return $this->index($request);
        }
        UserSession::updateAndAbort($request, 404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $is_admin = Functions::isAdminPath($request->path());
        $usePagination = !$is_admin;
        $tmpData = [
            'PageNum' => 0,
            'NumShown' => 12,
            'PagingFor' => 'sectionPanel',
            'Dir' => 'asc',
            'WithTrashed' => $is_admin,
            'BaseUrl' => $is_admin ? 'admin/store' : 'store',
            'ViewNum' => 0,
            'UseBaseMaker' => $request->ajax(),
            'Default' => [],
            'Version' => 1,
            'UseTitle' => true,
            'FullUrl' => !$request->ajax(),
            'ListUrl' => $request->path(),
            'UsePagination' => $usePagination,
            'Transform' => $usePagination 
                ? Categorie::TO_MINI_TRANSFORM
                : Categorie::TO_TABLE_ARRAY_TRANSFORM,
        ];   
        $section = Section::getNamed($request->section);
        if (Functions::testVar($section)) {
            $sh = $section->getSubHeading();
            $content = [
                'hasName' => 'true',
                'name' => $section->name,
                'hasTitle' => 'true',
                'title' => $section->title,
                'hasUrl' => 'true',
                'url' => $section->url,
                'hasDescription' => 'true',
                'description' => $section->description,
                'hasArticle' => 'true',
                'article' => $section->getArticle(true),
                'hasSubHeading' => !empty($sh) ? 'true' : '',
                'subHeading' => !empty($sh) ? $sh : '',
                'hasImage' => 'true',
                'image' => $section->getImageArray(),
                'thisURL' => $section->getFullUrl($tmpData['BaseUrl'], $tmpData['FullUrl']),
                'HttpVerb' => 'PATCH',
                'cancelUrl' => 'admin/store/section',
            ];
            $bcLinks = [];
            $bcLinks[] = self::getHomeBreadcumb();
            if ($is_admin) {
                $bcLinks[] = CmsController::getAdminBreadcrumb();
            }
            $bcLinks[] = Page::genBreadcrumb(
                'Our Produce Sections', 
                Section::genUrlFragment($tmpData['BaseUrl'], $tmpData['FullUrl'])
            );
            $breadcrumbs = Page::getBreadcrumbs(
                Page::genBreadcrumb(
                    'Section Modification Form', 
                    Section::genUrlFragment($tmpData['BaseUrl'], $tmpData['FullUrl'])
                ),
                $bcLinks
            );
            return self::getView(
                $request, 'cms.forms.edit.section', 'Modify Existing Store Section',
                $content, false, $breadcrumbs, null, 
                $is_admin ? CmsController::getAdminSidebar()
                : null
            );
        }
        UserSession::updateAndAbort($request, 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function destroy(Section $section)
    {
        //
    }

    public function showDelete(Request $request)
    {
        // display 'ARE YOU SURE' PAGE...
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
            'name' => 'required|max:255|string|min:3|unique:sections,name',
            'description' => 'required|max:255|string|min:3',
            'url' => [
                'required', 'max:255', 'string', 'min:3',
                'regex:'. Functions::getURLRegexStr(),
                'unique:sections,url'
            ],
        ];
    }

    /**
     * Get the validation rules that apply to the modification request.
     *
     * @return array
     */
    static public function modificationValidationRules($url, $name)
    {
        return [
            //
            'image' => 'nullable|file|image',
            'article' => 'required|max:255000|string|min:3',
            'subheading' => 'string|nullable|max:255',
            'title' => 'required|max:255|string|min:3',
            'name' => [
                'required', 'max:255', 'string', 'min:3',
                Rule::unique('sections', 'name')->ignore($name, 'name')
            ],
            'description' => 'required|max:255|string|min:3',
            'url' => [
                'required', 'max:255', 'string', 'min:3',
                'regex:'. Functions::getURLRegexStr(),
                Rule::unique('sections', 'url')->ignore($url, 'url')
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


    /// testing!!!

    public function test(Request $request)
    {
        //return static::getView($request, 'content.section', 'TEST-SECTION', [], true);
        return static::getView($request, 'content.items_list', 'TEST-SECTION', [], true);
    }
}
