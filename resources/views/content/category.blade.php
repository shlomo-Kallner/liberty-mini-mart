

@extends('content.template')
{{-- 
    the store inner view: 

    Should show a selection of 'Bestsellers' and the the list of Sections of the store.
--}}

@section('main-content')
    @parent

    @php
        $testing = false;

        use \App\Utilities\Functions\Functions;


        $sidebar2 = serialize(Functions::getContent($sidebar??''));
        //dd($sidebar, $sidebar2);
        $products2 = serialize(Functions::getContent($page['products']??''));
        $bestsellers2 = serialize(Functions::getContent($page['bestsellers']??'', ''));
        $currency2 = Functions::getContent($currency??'fa-usd','fa-usd');
        $filters2 = '';

        //dd($products2, $bestsellers2);
        
    @endphp

    <div class="row margin-bottom-40 ">

        @component('lib.themewagon.sidebar')
            @slot('menu')
                {!! $sidebar2 !!}
            @endslot
            @slot('filters')
                {!! $filters2 !!}
            @endslot
            @slot('products')
                {!! $bestsellers2 !!}
            @endslot
            @slot('currency')
                {{ $currency2 }}
            @endslot
        @endcomponent

        @component('lib.themewagon.content_list')
            @slot('sorting')
                {{ "" }}
            @endslot
            @slot('products')
                {!! $products2 !!}
            @endslot
            @slot('pageNumber')
                {{ '-1' }}
            @endslot
            @slot('productsPerPage')
                {{ '12' }}
            @endslot
            @slot('currency')
                {{ $currency2 }}
            @endslot
        @endcomponent

    </div>
@endsection


