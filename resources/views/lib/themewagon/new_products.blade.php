
@php

    $testing = false;
    use \App\Utilities\Functions\Functions;
    
    if (true) {
        $newProducts2 = Functions::getContent($newProducts??'','');
        $sampleProducts2 = Functions::getContent($sampleProducts??'');
    } else {
        $newProducts2 = serialize([
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
        ]);
        $sampleProducts2 = serialize([
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
        ]);
    }
    
    $menu2 = Functions::getContent($sidebar??'');
    //dd($sidebar2);
    $newProductsTitle2 = Functions::getBladedString($newProductsTitle??'','New Arrivals');
    $sampleProductsTitle2 = Functions::getBladedString($sampleProductsTitle??'','Three Items');
    $currency2 = Functions::getContent($currency??'','fa-usd');
    $filters2 = Functions::getContent($filters2??'');
    $bestsellers2 = Functions::getContent($bestsellers??'');

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
            {!! $newProductsTitle2 !!}
        @endslot
    @endcomponent
<!-- END SALE PRODUCT & NEW ARRIVALS -->

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
                    {{ $sampleProductsTitle2 }}
                @endslot
            @endcomponent
        <!-- END CONTENT -->
    </div>
<!-- END SIDEBAR & CONTENT -->




