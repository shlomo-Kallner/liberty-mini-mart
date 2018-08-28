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
        // display ALL Sections...
        return self::getView($request, 'content.catalog');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return self::getView($request, 'cms.forms.new.section', 'Create a New Store Section');
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
        if (isset($request->section)) {
            
            //dd($request->section);
            $section = Section::getNamed($request->section);
            //$section_items = Categorie::getCategoriesOfSection($section->id);
            //$section_items = $section->getCategories(false);
            $section_items = [];
            foreach ($section->categories as $cat) {
                $section_items[] = $cat->toMini('store');
            }
            $section_data = [
                'section' => $section,
                'items' => $section_items,
                'bestsellers' => Product::getBestsellers(),
                
            ];
            
            $breadcumbs = Page::getBreadcrumbs(
                Page::genBreadcrumb($section->name, $section->getFullUrl('store')),
                [
                    Page::genBreadcrumb('Store', 'store'),
                    Page::genBreadcrumb('Our Sections', 'store/section'),
                ]
            );
            return self::getView(
                $request, 'content.section', $section->title, $section_data, 
                false, $breadcumbs
            );
        } else {
            // get a listing of all sections... 
            $breadcumbs = Page::getBreadcrumbs(
                [
                    Page::genBreadcrumb('Store', 'store'),
                ],
                Page::genBreadcrumb('Our Sections', 'store/section')
            );
            // create a special 'content.sections' view for such a listing.. 
            // optionally add pagination... 
            return self::getView($request, 'content.catalog', 'Our Sections', null, false, $breadcumbs);
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
        return static::getView($request, 'content.section', 'TEST-SECTION', [], true);
    }
}
