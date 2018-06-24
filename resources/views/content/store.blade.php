

@extends('content.template')
{{-- 
    the store inner view: 

    Should show a selection of 'Bestsellers' and the the list of Sections of the store.
--}}

@php

    $testing = true;
    $fakeData = ''; // the old data was '123FAKEDATA321'..

    use \App\Utilities\Functions\Functions;

    if (!$testing) {
        $newProducts2 = serialize(Functions::getContent($newProducts??'',''));
        $sidebar2 = serialize(Functions::getContent($sidebar??'',''));
        $filters2 = serialize(Functions::getContent($filters??$fakeData,$fakeData));
        $bestsellers2 = serialize(Functions::getContent($bestsellers??$fakeData,$fakeData));
        $currency2 = Functions::getContent($currency??'fa-usd','fa-usd');

    } else {
        $newProducts2 = serialize(Functions::getContent($newProducts??'',''));
        $sidebar2 = serialize(Functions::getContent($sidebar??'',''));
        $filters2 = serialize([
            [
                'name' => 'Availability',
                'filter' => e('<div class="checkbox-list">
                        <label><input type="checkbox"> Not Available (3)</label>
                        <label><input type="checkbox"> In Stock (26)</label>
                    </div>')
            ],
            [
                'name' => 'Price',
                'filter' => e('<p>
                        <label for="amount">Range:</label>
                        <input type="text" id="amount" style="border:0; color:#f6931f; font-weight:bold;">
                    </p>
                    <div id="slider-range"></div>')
            ]
        ]);
        $bestsellers2 = serialize([
            [
                'url' => 'lib/themewagon/metronicShopUI/theme/shop-item.html',
                'img' => 'lib/themewagon/metronicShopUI/theme/assets/pages/img/products/k1.jpg',
                'alt' => 'Some Shoes in Animal with Cut Out',
                'price' => '31.00'
            ],
            [
                'url' => 'lib/themewagon/metronicShopUI/theme/shop-item.html',
                'img' => 'lib/themewagon/metronicShopUI/theme/assets/pages/img/products/k4.jpg',
                'alt' => 'Some Shoes in Animal with Cut Out',
                'price' => '23.00'
            ],
            [
                'url' => 'lib/themewagon/metronicShopUI/theme/shop-item.html',
                'img' => 'lib/themewagon/metronicShopUI/theme/assets/pages/img/products/k3.jpg',
                'alt' => 'Some Shoes in Animal with Cut Out',
                'price' => '86.00'
            ]
        ]);
        $currency2 = Functions::getContent($currency??'fa-usd','fa-usd');

    }
    

    if ($testing) {
        //dd($filters2); 
        //dd($newProducts2); 
        //dd($sidebar2); 
        //dd($bestsellers2); 
        //dd($currency2);
    } 
    
@endphp


@section('main-content')
    @parent

    <section class="bar background-white no-mb">
        <div class="container" {{-- data-animate="fadeInUp" --}} > 
        

            @if (false)
                @component('lib.themewagon.new_and_sales')
                    @slot('newProducts')
                        {!! $newProducts2 !!}
                    @endslot
                @endcomponent
            @endif
            
            @if (false)
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
            
            @if (false)
                
            <div class="row">
                <div class="col-md-12">
                    @component('lib.bootstrapious.feature_single_showcase_item')
                        @foreach ($page['article'] as $key => $item)
                            @slot($key)
                                {{ $item }}
                            @endslot
                        @endforeach
                    @endcomponent
                </div>
            </div>

            @endif
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
        
                @if (false)
                    @component('lib.themewagon.content_list')
                        @slot('sorting')
                            {{ "" }}
                        @endslot
                        @slot('products')
                            {{ '' }}
                        @endslot
                        @slot('pageNumber')
                            {{ '-1' }}
                        @endslot
                        @slot('productsPerPage')
                            {{ '12' }}
                        @endslot
                    @endcomponent
                @endif

                @if (false)
                    @component('lib.bootstrapious.feature_multiple_items')
                        @slot('heading')
                            {!! "OUR SECTIONS" !!}
                        @endslot
                    @endcomponent
                @endif

                
                @if (false)

                    @component('lib.themewagon.paginator')
                        
                    @endcomponent
            
                @endif

            </div>
        </div>
    </section>
    
@endsection

@section('js-extra')
    @parent

    
    
@endsection