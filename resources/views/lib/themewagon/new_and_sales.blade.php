
@php

    $testing = true;
    use \App\Utilities\Functions\Functions;

    if ($testing) {
        $newProducts2 = '';
    } else {
        $newProducts2 = Functions::getContent($newProducts??'');
    }
    

@endphp


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