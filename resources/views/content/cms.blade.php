@extends('content.template')

@section('main-content')
    @parent

    @php
        $testing = true;
        use \App\Utilities\Functions\Functions,
            \App\Page;

        $sidebar2 = Functions::getContent($sidebar??'');
        $page2 = Functions::getContent($page['article']??'');
        $sections2 = Functions::getContent($page['sections']['items']??'');
        $sections_paginator2 = Functions::getContent($page['sections']['pagination']??'');
        $users2 = Functions::getContent($page['users']['items']??'');
        $users_paginator2 = Functions::getContent($page['users']['pagination']??'');
        $pages2 = Functions::getContent($page['pages']['items']??'');
        $pages_paginator2 = Functions::getContent($page['pages']['pagination']??'');
        
          
           
        /*

            //TODO: use this
            $sidebar2[] = Page::genURLMenuItem(
                string $url, string $name, string $icon = '', 
                string $textTransform = '', string $cssExtraClasses = '', 
                string $iconAfter = '', string $role = ''
            ); 

            /// for each of the "create" Links below..

        */

        $sidebar2[] = Page::genURLMenuItem(
            'admin/section/create', 'Create a New Section', 'fa-shopping-cart', 
            '', '', 'fa-plus', 'button'
        );  
        $sidebar2[] = Page::genURLMenuItem(
            'admin/category/create', 'Create a New Category', 'fa-shopping-basket', 
            '', '', 'fa-plus', 'button'
        );  
        $sidebar2[] = Page::genURLMenuItem(
            'admin/product/create', 'Create a New Product', 'fa-shopping-bag', 
            '', '', 'fa-plus', 'button'
        );  
        $sidebar2[] = Page::genURLMenuItem(
            'admin/user/create', 'Create a New User', 'fa-address-book', 
            '', '', 'fa-plus', 'button'
        );  
        $sidebar2[] = Page::genURLMenuItem(
            'admin/page/create', 'Create a New Content Page', 'fa-newspaper-o', 
            '', '', 'fa-plus', 'button'
        );  
        $sidebar2[] = Page::genURLMenuItem(
            'admin/plan/create', 'Create a New Membership Plan', 'fa-lightbulb-o', 
            '', '', 'fa-plus','button'
        );  
            

        //dd($sidebar2);
    @endphp

    <div class="row margin-bottom-40">
        @component('lib.themewagon.sidebar')
            @slot('menu')
                {!! serialize($sidebar2) !!}
            @endslot
            @slot('sidebarClasses')
                {!! 'col-md-3 col-sm-5' !!}
            @endslot
        @endcomponent

        <div class="col-md-9 col-sm-7">
            
            <h1>{{ $page2['title'] }}</h1>
            <h2>{{ $page2['subheading'] }}</h2>

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

                <div class="collapse" id="collapsableSectionsPanel">

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <a class="btn btn-primary" href="{{ url('admin/section/create') }}" role="button">
                                Create a New Section
                            </a>
                        </div>
                    </div>
                         
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            @if (Functions::testVar($sections2) && false)
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
                                
                            @else
                                
                                <div class="well">
                                    <h4>Oooppps! No Sections Available for Display...</h4>
                                </div>
                                
                            @endif
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
                            
                            @if (Functions::testVar($users2) && false)
                                @component('cms.sections')
                                    @slot('users')
                                        {!! serialize($users2) !!}
                                    @endslot
                                    @if (Functions::testVar($users_paginator2))
                                        @slot('paginator')
                                            {!! serialize($users_paginator2) !!}
                                        @endslot
                                    @endif
                                @endcomponent
                                
                            @else
                                
                                <div class="well">
                                    <h4>Oooppps! No Users Available for Display...</h4>
                                </div>
                                
                            @endif

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
                            
                                @if (Functions::testVar($pages2) && false)
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
                                    
                                @else
                                    
                                    <div class="well">
                                        <h4>Oooppps! No Pages Available for Display...</h4>
                                    </div>
                                    
                                @endif
    
                            </div>
                    </div>
                    
                </div>
                
                <div id="cms-app">

                </div>

            @show
            
        </div>
    
    </div>

    
@endsection