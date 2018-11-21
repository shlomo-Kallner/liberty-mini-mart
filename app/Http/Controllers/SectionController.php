<?php

namespace App\Http\Controllers;

use App\Section,
    App\Page,
    App\Product,
    App\Categorie;
use Illuminate\Http\Request;

class SectionController extends MainController
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
        // get a listing of all sections... 
        $title = 'All Our Sections';
        $breadcumbs = Page::getBreadcrumbs(
            Page::genBreadcrumb($title, 'store/section'),
            [
                Page::genBreadcrumb('Store', 'store'),
            ]
        );
        $content = [
            'items' => Section::getAllWithTransform(
                Section::TO_MINI_TRANSFORM, 'asc', false, 'store',
                true, 1
            ),
            'bestsellers' => Product::getBestsellers(),
        ];
        // optionally add pagination... 
        return self::getView(
            $request, 'content.items_list', $title, 
            $content, false, $breadcumbs
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return self::getView($request, 'cms.forms.new.section', 'Create a New Store Section');
    }

    public function list(Request $request)
    {
        return Section::getNameListing();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        if (isset($request->section) && !empty($request->section)) {
            
            //dd($request->section, 'in show()');
            $section = Section::getNamed($request->section);
            //$section_items = Categorie::getCategoriesOfSection($section->id);
            //$section_items = $section->getCategories(false);
            if (false) {
                $pagingFor = 'section-content';
                if (Functions::testVar(
                    $pa = Section::getPagingVars(
                        $request, $pagingFor
                    )
                )
                ) {
                    $viewNumber = $pa['viewNum'];
                    $pageNum = $pa['pageNum'];
                } else {
                    $viewNumber = 0;
                    $pageNum = 0;
                }
                $numShown = 12;
                $dir = 'asc';
                $useTitle = true;
                $withTrashed = false;
                $section_data = Categorie::getForWithPagination(
                    $section->categories, 
                    Categorie::TO_MINI_TRANSFORM, $pageNum,
                    $numShown, $pagingFor, $request->url(), 
                    $request->ajax() ? 'api/store' : 'store', 
                    $dir, $viewNumber, $withTrashed, 
                    $useTitle, 1, []
                );
                $section_data['bestsellers'] = Product::getBestsellers();
            } else {
                $section_data = [
                    'items' => Categorie::getFor(
                        $section->categories, 
                        $request->ajax() ? 'api/store' : 'store', 
                        Categorie::TO_MINI_TRANSFORM, true, 1, 
                        $request->withTrashed??false, []
                    ),
                    'bestsellers' => Product::getBestsellers(),
                ];
            }
            $breadcumbs = Page::getBreadcrumbs(
                Page::genBreadcrumb($section->name, $section->getFullUrl('store')),
                [
                    Page::genBreadcrumb('Store', 'store'),
                    //Page::genBreadcrumb('All Our Sections', 'store/section'),
                ]
            );
            return self::getView(
                $request, 'content.items_list', $section->title, $section_data, 
                false, $breadcumbs
            );
        } else {
            return $this->index($request);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function edit(Section $section)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Section $section)
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

    public function test(Request $request)
    {
        //return static::getView($request, 'content.section', 'TEST-SECTION', [], true);
        return static::getView($request, 'content.items_list', 'TEST-SECTION', [], true);
    }
}
