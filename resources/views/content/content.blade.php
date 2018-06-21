
@extends('content.template')

@section('main-content')
    @parent

    <!-- BEGIN SIDEBAR & CONTENT -->
    <div class="row margin-bottom-40">
          

        @component('lib.themewagon.sidebar')
            @slot('menu')
                {!! serialize($sidebar) !!}
            @endslot
            @slot('sidebarClasses')
                {{ 'col-md-3 col-sm-3' }}
            @endslot
        @endcomponent

        @component('lib.themewagon.content')
            @slot('containerCss')
                {!! 'col-md-9 col-sm-9' !!}
            @endslot
            @slot('pageHeader')
                {!! $page['header'] !!}
            @endslot
            @slot('articleHeader')
                {!! $page['article']['header'] !!}
            @endslot
            @slot('subheading')
                {!! $page['article']['subheading'] !!}
            @endslot
            @slot('img')
                {!! $page['article']['img'] !!}
            @endslot
            @slot('imgAlt')
                {!! $page['article']['imgAlt'] !!}
            @endslot
            @slot('article')
                {!! $page['article']['article'] !!}
            @endslot
        @endcomponent

    </div>
    <!-- END SIDEBAR & CONTENT -->


@endsection