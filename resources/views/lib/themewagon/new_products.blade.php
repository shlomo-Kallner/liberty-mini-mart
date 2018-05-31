
@php

    $testing = true;
    use \App\Utilities\Functions\Functions;
    
    $newProducts2 = Functions::getContent($newProducts??'');
    $sampleProducts2 = Functions::getContent($sampleProducts??'');
    $productsTitle2 = Functions::getBladedString($productsTitle??'','Three Items');
    $currency2 = Functions::getContent($currency??'');
    $menu2 = Functions::getContent($sidebar??'');
    //dd($sidebar2);
    $filters2 = Functions::getContent($filters2??'');
    $bestsellers2 = Functions::getContent($bestsellers??'');

@endphp

@if(true)
    <!-- BEGIN SALE PRODUCT & NEW ARRIVALS -->
        @component('lib.themewagon.product_gallery')
            @slot('products')
                {!! $newProducts2 !!}
            @endslot
            @slot('containerClasses')
                {{ "row margin-bottom-40 margin-top-30" }}
            @endslot
            @slot('sizeClass')
                {{ "col-md-12" }}
            @endslot
            @slot('productClass')
                {{ "sale-product" }}
            @endslot
            @slot('owlClass')
                {{ "owl-carousel5" }}
            @endslot
            @slot('title')
                {{ "New Arrivals" }}
            @endslot
        @endcomponent
    <!-- END SALE PRODUCT & NEW ARRIVALS -->
@endif

@if(false)
    <!-- BEGIN SIDEBAR & CONTENT -->
    <div class="row margin-bottom-40 ">
    
        @component('lib.themewagon.sidebar')
            @slot('menu')
                {!! $menu2 !!}
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
            
        <!-- BEGIN CONTENT -->
        @component('lib.themewagon.product_gallery')
            @slot('products')
                {!! $sampleProducts2 !!}
            @endslot
            @slot('sizeClass')
                {{ "col-md-9 col-sm-8" }}
            @endslot
            @slot('owlClass')
                {{ "owl-carousel3" }}
            @endslot
            @slot('title')
                {{ $productsTitle2 }}
            @endslot
        @endcomponent
        <!-- END CONTENT -->
    </div>
    <!-- END SIDEBAR & CONTENT -->

@else
    @component('lib.themewagon.sidebar_with_content')
        @slot('products')
            {!! $sampleProducts2 !!}
        @endslot
        @slot('menu')
            {!! $menu2 !!}
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
        @slot('productsTitle')
            {{ $productsTitle2 }}
        @endslot
    @endcomponent
@endif



