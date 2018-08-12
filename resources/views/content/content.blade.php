
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

        @component('lib.themewagon.article')
            @slot('containerCss')
                {!! 'col-md-9 col-sm-9' !!}
            @endslot
            @foreach ($page['article'] as $key => $item)
                @slot($key)
                    {!! $item !!}
                @endslot
            @endforeach
        @endcomponent

    </div>
    <!-- END SIDEBAR & CONTENT -->


@endsection