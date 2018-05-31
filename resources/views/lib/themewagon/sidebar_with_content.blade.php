

@php

$testing = true;
use \App\Utilities\Functions\Functions;

$products2 = Functions::getContent($products??'');
$productsTitle2 = Functions::getBladedString($productsTitle??'');
$currency2 = Functions::getBladedString($currency??'');
$sidebar2 = Functions::getContent($sidebar??'');
$filters2 = Functions::getContent($filters2??'');
$bestsellers2 = Functions::getContent($bestsellers??'');

@endphp

<!-- BEGIN SIDEBAR & CONTENT -->
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
        
    <!-- BEGIN CONTENT -->
        @component('lib.themewagon.product_gallery')
            @slot('products')
                {!! $products2 !!}
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

