

@extends('content.template')
{{-- 
    a section's inner view: 

    Should show the list of categories in the section + a side bar with filters & bestsellers..
--}}

@php
    $testing = false;

    use \App\Utilities\Functions\Functions;

    $items2 = serialize(Functions::getContent($page['items']??''));

    $sidebar2 = serialize(Functions::getContent($sidebar??''));
    $bestsellers2 = serialize(Functions::getContent($page['bestsellers']??'', ''));
    $currency2 = Functions::getContent($cart['currencyIcon']??'','fa-usd');
    $filters2 = serialize(Functions::getContent($page['filters']??'', ''));
    
    $itemsPerPage2 = intval(Functions::getContent($page['itemsPerPage']??'', '12'));
    $pageNumber2 = intval(Functions::getContent($page['pageNumber']??'', '-1'));
    $sorting2 = serialize(Functions::getContent($page['sorting']??'', ''));
@endphp

@section('main-content')
    @parent

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
                {!! $sorting2 !!}
            @endslot
            @slot('products')
                {!! $items2 !!}
            @endslot
            @slot('pageNumber')
                {{ $pageNumber2 }}
            @endslot
            @slot('productsPerPage')
                {{ $itemsPerPage2 }}
            @endslot
            @slot('currency')
                {{ $currency2 }}
            @endslot
        @endcomponent

    </div>
@endsection

