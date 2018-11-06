

@extends('content.template')
{{-- 
    a section's inner view: 

    Should show the list of categories in the section + a side bar with filters & bestsellers..
--}}

@php
    $testing = false;

    use \App\Utilities\Functions\Functions;

    $section2 = '';
    $categories2 = serialize(Functions::getContent($page['items']??''));

    $sidebar2 = serialize(Functions::getContent($sidebar??''));
    $bestsellers2 = serialize(Functions::getContent($page['bestsellers']??'', ''));
    $currency2 = Functions::getContent($cart['currencyIcon']??'','fa-usd');
    $filters2 = serialize(Functions::getContent($page['filters']??'', ''));
    
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
                {{ "" }}
            @endslot
            @slot('products')
                {!! $categories2 !!}
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

