@extends('content.base')

@section('main-content')
    @parent

    @php
        $testing = true;
        use \App\Utilities\Functions\Functions,
            \App\Page;
        
        $useVueEl = false;
        $useColapse = false;
        if (!$useVueEl) {
            $sidebar2 = Functions::getContent($sidebar??[],[]);
            $article2 = Functions::getContent($page['article']??[],[]);
            if ($useColapse) {
                $sections2 = Functions::getContent($page['sections']['items']??[],[]);
                $sections_paginator2 = Functions::getContent($page['sections']['pagination']??[],[]);
                $users2 = Functions::getContent($page['users']['items']??[],[]);
                $users_paginator2 = Functions::getContent($page['users']['pagination']??[],[]);
                $pages2 = Functions::getContent($page['pages']['items']??[],[]);
                $pages_paginator2 = Functions::getContent($page['pages']['pagination']??[],[]);
    
                /// THESE TWO ARE WISHLIST ITEMS!!
                $plans2 = Functions::getContent($page['plans']['items']??[],[]);
                $plans_paginator2 = Functions::getContent($page['plans']['pagination']??[],[]);
    
                /// THIS IS A WISHLIST ITEM!!
                if (Functions::testVar($plans2)) {
                    $sidebar2[] = Page::genURLMenuItem(
                        'admin/plan/create', 'Create a New Membership Plan', 'fa-lightbulb-o', 
                        '', '', 'fa-plus','button'
                    );  
                }
                
                //dd($sidebar2);
    
                //dd($sidebar2);
                //dd($page);
                //dd($page['sections']);
                // dd($sections2, $sections_paginator2);
                // dd($users2, $users_paginator2);
                //dd($pages2, $pages_paginator2);
                //dd($plans2, $plans_paginator2);
            } else {

                $items2 = Functions::toBladableContent(Functions::getContent($page['items']??''));
            
                $sidebar2 = Functions::toBladableContent(Functions::getContent($sidebar??''));
                $currency2 = Functions::getContent($cart['currencyIcon']??'','fa-usd');
                $filters2 = Functions::toBladableContent(Functions::getContent($page['filters']??'', ''));
                
                if (!Functions::hasPropKeyIn($page, 'pagination')) {
                    $itemsPerPage2 = intval(Functions::getContent($page['itemsPerPage']??'', '12'));
                    $pageNumber2 = intval(Functions::getContent($page['pageNumber']??'', '-1'));
                } else {
                    $itemsPerPage2 = intval(Functions::getContent($page['pagination']['numItemsPerPage']??'', '12'));
                    $pageNumber2 = intval(Functions::getContent($page['pagination']['currentPage']??'', '-1'));
                }
                $sorting2 = Functions::toBladableContent(Functions::getContent($page['sorting']??'', ''));
            }
        }
        //dd($page);
        
    @endphp

    @if ($useVueEl)

        <div id="cms-app"></div>

    @else

        <div class="row margin-bottom-40">
            
            @component('lib.themewagon.sidebar')
                @slot('menu')
                    {!! Functions::toBladableContent($sidebar2) !!}
                @endslot
                @slot('sidebarClasses')
                    {!! 'col-md-3 col-sm-5' !!}
                @endslot
            @endcomponent

            <div class="col-md-9 col-sm-7">
                @component('lib.themewagon.article')
                    @foreach ($article2 as $key => $item)
                        @slot($key)
                            {!! Functions::toBladableContent($item) !!}
                        @endslot
                    @endforeach
                @endcomponent

                @if ($useColapse)

                    @section('cms-content')

                        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapsableSectionsPanel" aria-expanded="false" aria-controls="collapsableSectionsPanel">
                            Display Sections
                        </button>
                        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapsableUsersPanel" aria-expanded="false" aria-controls="collapsableUsersPanel">
                            Display Users
                        </button>
                        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapsablePagesPanel" aria-expanded="false" aria-controls="collapsablePagesPanel">
                            Display Pages
                        </button>

                        <hr>

                        <div class="collapse" id="collapsableSectionsPanel">

                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <a class="btn btn-primary pull-left" href="{{ url('admin/section/create') }}" role="button">
                                        Create a New Section
                                    </a>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    
                                    @component('cms.sections')
                                        @slot('sections')
                                            {!! serialize($sections2) !!}
                                        @endslot
                                        @if (Functions::testVar($sections_paginator2))
                                            @slot('paginator')
                                                {!! serialize($sections_paginator2) !!}
                                            @endslot
                                        @endif
                                    @endcomponent
                                        
                                </div>
                            </div>

                        </div>

                        <div class="collapse" id="collapsableUsersPanel">
                            
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <a class="btn btn-primary" href="{{ url('admin/user/create') }}" role="button">Create a New User</a>
                                </div>
                            </div>
                                    
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    
                                    @component('cms.users')
                                        @slot('users')
                                            {!! serialize($users2) !!}
                                        @endslot
                                        @if (Functions::testVar($users_paginator2))
                                            @slot('paginator')
                                                {!! serialize($users_paginator2) !!}
                                            @endslot
                                        @endif
                                    @endcomponent

                                </div>
                            </div>

                        </div>

                        <div class="collapse" id="collapsablePagesPanel">
                            
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <a class="btn btn-primary" href="{{ url('admin/page/create') }}" role="button">Create a New Page</a>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    
                                    @component('cms.pages')
                                        @slot('pages')
                                            {!! serialize($pages2) !!}
                                        @endslot
                                        @if (Functions::testVar($pages_paginator2))
                                            @slot('paginator')
                                                {!! serialize($pages_paginator2) !!}
                                            @endslot
                                        @endif
                                    @endcomponent

                                </div>
                            </div>
                            
                        </div>

                    @show
                    
                @else

                    @component('lib.themewagon.content_list')
                        @slot('sorting')
                            {!! $sorting2 !!}
                        @endslot
                        @slot('items')
                            {!! $items2 !!}
                        @endslot
                        @slot('pageNumber')
                            {{ $pageNumber2 }}
                        @endslot
                        @slot('itemsPerPage')
                            {{ $itemsPerPage2 }}
                        @endslot
                        @slot('currency')
                            {{ $currency2 }}
                        @endslot
                    @endcomponent
                
                @endif
                
            </div>
        
        </div>
    
    @endif

@endsection

@section('js-preloaded')
    @parent
    @if ($useVueEl)
        <script>
            window.Laravel.admin = '@json($page)';
        </script>
    @endif
@endsection

@section('js-main')
    @parent
    @php
        // dd(asset('js/admin.js'), asset(mix('js/admin.js')));
    @endphp
    @if ($useVueEl)
        <script src="{{ asset(mix('js/admin_vue.js')) }}" type="text/javascript"></script>
    @else
        <script src="{{ asset(mix('js/admin_blade.js')) }}" type="text/javascript"></script>
    @endif
@endsection

