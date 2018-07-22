<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request,
    Illuminate\Support\Facades\Session;
use App\Http\Controllers\UserController,
    App\Page,
    App\Section,
    App\Categorie,
    App\Product;

class CmsController extends MainController 
{
    
    public function __construct($name = '', $titleNameSep = ' | ') 
    {
        parent::__construct($name, $titleNameSep);
        $this->middleware(
            function ($request, $next) {
                if ($request->session()->has('user.is_admin')) {
                    return $next($request);
                } else {
                    $request->session()->reflash();
                    
                    //$request->session()->flash('redirectFullUrl', $request->fullUrl());
                    $request->session()->flash('redirectPath', $request->path());
                    
                    return redirect('signin/' . UserController::pagePathJoin($request->path()));
                }
            }
        );
    }

    public function index(Request $request)
    {
        //$sections = Section::getAllWithPagination();
        $sections = Section::getAllModels();
        foreach ($sections as $section) {
            //$section['categories'] = Categorie::getCategoriesOfSectionWithPagination($section['id'], ... );
            $section['categories'] = Categorie::getCategoriesOfSection($section['id']);
            dd($section);
        }
        dd($sections);
        return self::getView(
            'content.cms', '', [
                'article' => [
                    'title' => 'Welcome to OUR DASHBOARD!',
                    'subheading' => 'Here you can add, remove or edit Sections, Categories, Products and Other Content on this site!'
                ],
                'sections' => [
                    'items' => $sections,
                    'pagination' => ''
                ],
                /*
                    ''=> [
                        'items' => $->toArray(),
                        'pagination' => ''
                    ],
                */
            ]
        );
    }


}
