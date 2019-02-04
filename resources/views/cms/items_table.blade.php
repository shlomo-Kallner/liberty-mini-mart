@extends('content.base')

@section('main-content')
    @parent

        
    @php
        $testing = true;
        use \App\Utilities\Functions\Functions;

        $sidebar2 = Functions::getContent($sidebar??[],[]);
        $article2 = Functions::getContent($page['article']??[],[]);
        $items2 = Functions::getContent($page['items']??[],[]);
        $currency2 = Functions::getContent($cart['currencyIcon']??'','fa-usd');
        $filters2 = Functions::toBladableContent(Functions::getContent($page['filters']??'', ''));
        
        if (!Functions::hasPropKeyIn($page, 'pagination')) {
            $itemsPerPage2 = intval(Functions::getContent($page['itemsPerPage']??'', '12'));
            $pageNumber2 = intval(Functions::getContent($page['pageNumber']??'', '-1'));
            $paginator2 = Functions::toBladableContent($page['pagination']??[], []);
        } else {
            $itemsPerPage2 = intval(Functions::getContent($page['pagination']['numItemsPerPage']??'', '12'));
            $pageNumber2 = intval(Functions::getContent($page['pagination']['currentPage']??'', '-1'));
            $paginator2 = Functions::toBladableContent($page['pagination']??[], []);
        }
        $sorting2 = Functions::toBladableContent(Functions::getContent($page['sorting']??'', ''));
        // $paginator2 = Functions::getUnBladedContent($paginator??[],[]);
        $type2 = Functions::getBladedString($page['type']??'', '');
       
        //dd($items2);
        
    @endphp

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

            @component('cms.items_table_list')
                @slot('sorting')
                    {!! $sorting2 !!}
                @endslot
                @slot('items')
                    {!! Functions::toBladableContent($items2) !!}
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
                @slot('containerCss')
                    {!! 'col-md-12 col-sm-12 col-lg-12' !!}
                @endslot
                @slot('type')
                    {!! $type2 !!}
                @endslot
                @slot('paginator')
                    {!! $paginator2 !!}
                @endslot
            @endcomponent
            
        </div>

    </div>

@endsection

@section('js-main')
    @parent
    @php
        // dd(asset('js/admin_blade.js'), asset(mix('js/admin_blade.js')));
    @endphp
    
    <script src="{{ asset(mix('js/admin_blade.js')) }}" type="text/javascript"></script>
@endsection
