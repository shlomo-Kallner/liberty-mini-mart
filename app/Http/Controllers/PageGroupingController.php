<?php

namespace App\Http\Controllers;

use App\PageGrouping,
    App\PageGroup,
    Illuminate\Http\Request,
    App\Utilities\Functions\Functions;

class PageGroupingController extends MainController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * Display a "Name Listing" of the resource.
     *
     * @param  \Illuminate\Http\Request $request - the request object...
     * 
     * @return \Illuminate\Http\Response
     */
    public function orderingList(Request $request)
    {
        $is_admin = true; //Functions::isAdminPath($request->path());
        $pageGroup = PageGroup::getNamed(
            $request->input('menu'), $is_admin, null, false
        );
        $res = [];
        if (Functions::testVar($pageGroup)) {
            $group = PageGrouping::getGroup($pageGroup, 'asc', $is_admin);
            if (Functions::testVar($group)) {
                foreach ($group as $key => $page) {
                    $res[] = $page->order;
                }
            }
        }
        return $res;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
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
     * @param  \App\PageGrouping  $pageGrouping
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PageGrouping  $pageGrouping
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
     * @param  \App\PageGrouping  $pageGrouping
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PageGrouping  $pageGrouping
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
    }

    public function showDelete(Request $request)
    {
        //
    }
}
