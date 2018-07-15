@extends('content.template')

@section('main-content')
    @parent

    @php
        $testing = true;
        use \App\Utilities\Functions\Functions;

        $sidebar2 = serialize(Functions::getContent($sidebar??''));
        $page2 = Functions::getContent($page['article']??'');
        $sections2 = Functions::getContent($page['sections']['items']??'');
        $paginator2 = Functions::getContent($page['sections']['pagination']??'');
    @endphp

    <div class="row margin-bottom-40">
        @component('lib.themewagon.sidebar')
            @slot('menu')
                {!! $sidebar2 !!}
            @endslot
            @slot('sidebarClasses')
                {!! 'col-md-3 col-sm-5' !!}
            @endslot
        @endcomponent

        <div class="col-md-9 col-sm-7">
            
            <h1>{{ $page2['title'] }}</h1>
            <h2>{{ $page2['subheading'] }}</h2>

            @section('cms-content')

                <div class="row">
                    
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <h3>Sections:</h3>
                    </div>
                    
                </div>
                
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        @if (Functions::testVar($sections2) && false)
                            @component('cms.sections')
                                @slot('sections')
                                    {!! serialize($sections2) !!}
                                @endslot
                                @if (Functions::testVar($paginator2))
                                    @slot('paginator')
                                        {!! serialize($paginator2) !!}
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
                
                <div id="cms-app">

                </div>

            @show
            
        </div>
    
    </div>

    
@endsection