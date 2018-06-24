
@php

    $testing = true;
    use \App\Utilities\Functions\Functions;
    
    //dd($productClass);
    
    if (!$testing) {
        $products2 = Functions::getUnBladedContent($products??'','');
    } else {
        $products2 = [
            [
                'extraOuterCss' => '',
                'img' => 'lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model1.jpg',
                'name' => 'Berry Lace Dress',
                'id' => '1',
                'url' => 'lib/themewagon/metronicShopUI/theme/shop-item.html',
                'price' => '29.00',
                'sticker' => 'sticker-sale',
            ],
            [
                'extraOuterCss' => '',
                'img' => 'lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model2.jpg',
                'name' => 'Berry Lace Dress',
                'id' => '2',
                'url' => 'lib/themewagon/metronicShopUI/theme/shop-item.html',
                'price' => '29.00',
                'sticker' => '',
            ],
            [
                'extraOuterCss' => '',
                'img' => 'lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model6.jpg',
                'name' => 'Berry Lace Dress',
                'id' => '3',
                'url' => 'lib/themewagon/metronicShopUI/theme/shop-item.html',
                'price' => '29.00',
                'sticker' => '',
            ],
            [
                'extraOuterCss' => '',
                'img' => 'lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model4.jpg',
                'name' => 'Berry Lace Dress',
                'id' => '4',
                'url' => 'lib/themewagon/metronicShopUI/theme/shop-item.html',
                'price' => '29.00',
                'sticker' => 'sticker-new',
            ],
            [
                'extraOuterCss' => '',
                'img' => 'lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model5.jpg',
                'name' => 'Berry Lace Dress',
                'id' => '5',
                'url' => 'lib/themewagon/metronicShopUI/theme/shop-item.html',
                'price' => '29.00',
                'sticker' => '',
            ],
            [
                'extraOuterCss' => '',
                'img' => 'lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model3.jpg',
                'name' => 'Berry Lace Dress',
                'id' => '6',
                'url' => 'lib/themewagon/metronicShopUI/theme/shop-item.html',
                'price' => '29.00',
                'sticker' => '',
            ],
            [
                'extraOuterCss' => '',
                'img' => 'lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model7.jpg',
                'name' => 'Berry Lace Dress',
                'id' => '7',
                'url' => 'lib/themewagon/metronicShopUI/theme/shop-item.html',
                'price' => '29.00',
                'sticker' => '',
            ],
        ];
    }
    
    $containerClasses2 = Functions::getBladedString($containerClasses??'');
    $sizeClass2 = Functions::getBladedString($sizeClass??'');
    $productClass2 = Functions::getBladedString($productClass??'');
    $owlClass2 = Functions::getBladedString($owlClass??'');
    $title2 = Functions::getBladedString($title??'');
    $currency2 = Functions::getBladedString($currency??'','fa-usd');

    



@endphp

@if(Functions::testVar($products2))

    <!-- BEGIN PRODUCT GALLERY -->
    @if( Functions::testVar($containerClasses2) )
    <div class="{{ $containerClasses2 }}">
    @endif        
        
        @if( Functions::testVar($sizeClass2) )
        <div class="{{ $sizeClass2 }} {{ $productClass2 }}">
        @endif
            @if( Functions::testVar($title2) )
            <h2>{{ $title2 }}</h2>
            @endif

            @if (Functions::testVar($owlClass2))
                <div class="owl-carousel {{ $owlClass2 }}">
            @endif
                    
                @foreach ($products2 as $product)
                    @component('lib.themewagon.product_mini')
                        @foreach ($product as $key => $item)
                            @slot($key)
                                {{$item}}
                            @endslot
                            @slot('currency')
                                {!! $currency2 !!}
                            @endslot
                        @endforeach
                    @endcomponent
                @endforeach

            @if (Functions::testVar($owlClass2))
                </div>
            @endif
        @if( Functions::testVar($sizeClass2) )
        </div>
        @endif
    @if( Functions::testVar($containerClasses2) )        
    </div>
    @endif
    <!-- END PRODUCT GALLERY -->

@endif
