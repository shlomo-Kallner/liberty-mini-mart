<?php

namespace App\Http\Controllers;

use App\Categorie,
    App\Utilities\Functions\Functions,
    App\Product,
    App\Section,
    App\Page;
use Illuminate\Http\Request;

class CategorieController extends MainController
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
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return self::getView('cms.forms.new.category', 'Create a New Category');
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
     * @param  \App\Categorie  $categorie
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //dd($categorie);
        //dd($request->section, $request->category);
        //$categorie
        $sect = Section::where('url', $request->section)->first();
        //dd($sect->id);
        //dd($sect);
        if (Functions::testVar($sect)) {
            $cat = Categorie::where(
                [
                    ['section_id', $sect->id],
                    ['url', $request->category]
                ]
            )->first();
            // 'store/section/{section}/category/{category}/product/{product}'...
            $sect_url = 'store/section/'. $sect->url;
            $cat_url = $sect_url . '/category/' . $request->category;
            $breadcrumbs = Page::getBreadcrumbs( 
                Page::genBreadcrumb($cat->title, $cat_url),
                [
                    Page::genBreadcrumb('Store', 'store'),
                    Page::genBreadcrumb($sect->title, $sect_url)
                ]
            );
            //dd($cat);
            // getting the products of the category..
            self::$data['products'] = Product::getProductsForCategory($cat->id, 'mini', $cat_url);
            //dd($products);
            return parent::getView('content.category', $request->category, [], false, $breadcrumbs);
        } 
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Categorie  $categorie
     * @return \Illuminate\Http\Response
     */
    public function edit(Categorie $categorie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Categorie  $categorie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Categorie $categorie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Categorie  $categorie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Categorie $categorie)
    {
        //
    }

    public function showDelete(Request $request)
    {
        // display 'ARE YOU SURE' PAGE...
    }

    public function test(Request $request)
    {
        return static::getView('content.category', 'DEMO-CATEGORY', [], true);
    }

}
