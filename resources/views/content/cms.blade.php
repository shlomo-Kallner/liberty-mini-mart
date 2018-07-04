@extends('content.template')

@section('main-content')
    @parent

    @php
        $testing = true;
        use \App\Utilities\Functions\Functions;

        $sidebar2 = serialize(Functions::getContent($sidebar??''));
        $page2 = Functions::getContent($page['article']??'');
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
                
                <div id="users-app">

                </div>
            @show
            
        </div>
    
    </div>

    
@endsection