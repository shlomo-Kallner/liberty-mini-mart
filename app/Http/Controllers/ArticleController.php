<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;
use App\Page;
use App\Image;

class ArticleController extends MainController
{
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
        return self::getView(
            $request, 'cms.forms.new.article', 'Here You Can Create a New Article',
            [], false, Page::getBreadcrumbs(
                Page::getBreadcrumbs('Create An Article', $request->path()),
                Page::genBreadcrumb('Admin Dashboard', 'admin')
            )
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
        //
        Article::createNew(
            $request->article, $request->header??'',
            $request->image??'', $request->subheading??''
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Article $article)
    {
        return self::getTemplateView(
            $request, $article->header, ['article' => $article->toContentArray()],
            false, Page::getBreadcrumbs(
                Page::genBreadcrumb('Article: '. $article->header, $request->path()),
                Page::genBreadcrumb('Admin Dashboard', 'admin')
            )
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Article $article)
    {
        return self::getView(
            $request, 'cms.forms.edit.article', 'Here You Can Edit an Article',
            ['article' => $article->toContentArray()], false, 
            Page::getBreadcrumbs(
                Page::getBreadcrumbs('Create An Article', $request->path()),
                Page::genBreadcrumb('Admin Dashboard', 'admin')
            )
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        //
    }
    public function showDelete(Request $request, Article $article)
    {
        // display 'ARE YOU SURE' PAGE...
        return self::getView(
            $request, 'cms.forms.delete.article', 'Are You Sure You Want to Delete an Article?',
            ['article' => $article->toContentArray()], false, 
            Page::getBreadcrumbs(
                Page::getBreadcrumbs('Create An Article', $request->path()),
                Page::genBreadcrumb('Admin Dashboard', 'admin')
            )
        );
    }
}
