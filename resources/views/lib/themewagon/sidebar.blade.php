
@php

    $testing = false;
    $fakeData = ''; // the old data was '123FAKEDATA321'..

    use \App\Utilities\Functions\Functions,
        \App\Utilities\IterationStack\IterationStack,
        \App\Utilities\IterationStack\IterationFrame;

    //dd($testing);
    $menu2 = Functions::getContent($menu??$fakeData,$fakeData);
    //dd($menu2);
    if (!$testing) {
        $filters2 = Functions::getContent($filters??$fakeData,$fakeData);
        $products2 = Functions::getContent($products??$fakeData,$fakeData);  
    } else {
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
        $products2 = serialize([
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
    }
    //dd($filters2);
    //dd($products2);
    $currency2 = Functions::getContent($currency??'fa-usd','fa-usd');
    //dd($currency2);

    $sidebarClasses2 = Functions::getBladedString($sidebarClasses??'col-md-3 col-sm-5','col-md-3 col-sm-5');
@endphp

{{-- Initially intended to avoid doing exactly THIS (testing the primary slots), 
     but time has shown THIS the wiser course of ACTION... --}}

@if (Functions::testVar($menu2) || Functions::testVar($filters2) || Functions::testVar($products2))
    <!-- BEGIN SIDEBAR -->
    <div class="sidebar {{ $sidebarClasses2 }}">

        @if (Functions::testVar($menu2))
            @component('lib.themewagon.sidebar_menu')
                @slot('menu')
                    {!! $menu2 !!}
                @endslot
            @endcomponent
        @endif
        
        @if (Functions::testVar($filters2))
            @component('lib.themewagon.sidebar_filters')
                @slot('filters')
                    {!! $filters2 !!}
                @endslot
                @slot('currency')
                    {!! $currency2 !!}
                @endslot
                @slot('title')
                    {!! "Filters" !!}
                @endslot
            @endcomponent
        @endif
    
        @if (Functions::testVar($products2))
            @component('lib.themewagon.sidebar_products')
                @slot('products')
                    {!! $products2 !!}
                @endslot
                @slot('currency')
                    {!! $currency2 !!}
                @endslot
                @slot('title')
                    {!! "Bestsellers" !!}
                @endslot
            @endcomponent
        @endif
            
    </div>
    <!-- END SIDEBAR -->
@endif



