
@extends('content.template')

@section('main-content')
    @parent

    @php
        use \App\Utilities\Functions\Functions,
        \App\Page;

        $sidebar2 = Functions::getContent($sidebar??[],[]);
        $article2 = Functions::getContent($page['article']??[],[]);
        $bestsellers2 = Functions::getContent($page['bestsellers']??'', '');
        $currency2 = Functions::getContent($currency??'fa-usd','fa-usd');
        
    @endphp

    <!-- BEGIN SIDEBAR & CONTENT -->
    <div class="row margin-bottom-40">
          

        @component('lib.themewagon.sidebar')
            @slot('menu')
                {!! Functions::toBladableContent($sidebar2) !!}
            @endslot
            @slot('sidebarClasses')
                {{ 'col-md-3 col-sm-3' }}
            @endslot
            @slot('products')
                {!! Functions::toBladableContent($bestsellers2) !!}
            @endslot
            @slot('currency')
                {{ $currency2 }}
            @endslot
        @endcomponent

        @component('lib.themewagon.article-comp')
            @slot('containerCss')
                {!! 'col-md-9 col-sm-9' !!}
            @endslot
            @foreach ($article2 as $key => $item)
                @slot($key)
                    {!! Functions::toBladableContent($item) !!}
                @endslot
            @endforeach
        @endcomponent

    </div>
    <!-- END SIDEBAR & CONTENT -->


@endsection