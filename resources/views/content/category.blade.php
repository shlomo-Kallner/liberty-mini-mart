

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
        $filters2 = '';
        $bestsellers2 = '';
        $currency2 = 'fa-usd';

    @endphp

    <div class="row margin-bottom-40 ">

        @component('lib.themewagon.sidebar')
            @slot('menu')
                {!! $sidebar2 !!}
            @endslot
            @slot('filters')
                {!! $filters2 !!}
            @endslot
            @slot('bestsellers')
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


