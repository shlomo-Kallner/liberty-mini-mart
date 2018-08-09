<?php

namespace App\Http\Controllers;

use App\Section,
    App\Page,
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
        return self::getView('content.catalog');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return self::getView('cms.forms.new.section', 'Create a New Store Section');
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
            
            dd($request->section);
            $section = Section::getNamed($request->section);
            $section_items = Categorie::getCategoriesOfSection($section->id);
            $section_data = [
                'section' => $section,
                'items' => $section_items
            ];
            
            $breadcumbs = Page::getBreadcrumbs(
                [
                    Page::genBreadcrumb('Store', 'store'),
                    Page::genBreadcrumb('Our Sections', 'store/section'),
                ],
                Page::genBreadcrumb($section->name, 'store/section/'. $section->url)
            );
            return self::getVeiw(
                'content.section', $section->title, $section_data, 
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
            return self::getView('content.catalog', 'Our Sections', null, false, $breadcumbs);
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
        return static::getView('content.section', 'TEST-SECTION', [], true);
    }
}
