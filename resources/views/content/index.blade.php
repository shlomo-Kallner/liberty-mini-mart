

@extends('content.template')
{{-- Will be placing our "index" or "Home" page content in this view/file. --}}

@section('css-extra-fonts')
    @parent

    {{-- the font to be placed in a yield or in a child (extending) view.. --}}
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900&amp;subset=all" rel="stylesheet" type="text/css">
    <!--- fonts for slider on the index page -->  

@endsection


@section('css-preloaded-local')
    {{-- page local css --}}
    @parent


@endsection


@section('main-content')
    @parent

    @php
        $testing = false;
        use \App\Utilities\Functions\Functions;

        if (!$testing) {
            $newProducts2 = serialize(Functions::getContent($page['newProducts']['products']??'',''));
            $newProductsTitle2 = Functions::getContent($page['newProducts']['title']??'','New Arrivals');
            $sampleProducts2 = serialize(Functions::getContent($page['sampleProducts']['products']??''));
            $sampleProductsTitle2 = Functions::getContent($page['sampleProducts']['title']??'','Three Sample Items');
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
            $newProductsTitle2 = 'New Arrivals';
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
            $sampleProductsTitle2 = 'Three Sample Items';
        }
        $sidebar2 = serialize(Functions::getContent($sidebar??''));
        $currency2 = Functions::getContent($cart['currencyIcon']??'','fa-usd');
        $bestsellers2 = serialize(Functions::getContent($page['bestsellers']??''));

        // Filters and Membership Pricings are currently WISH-LIST ITEMS!!!
        $filters2 = serialize(Functions::getContent($page['filters']??'', []));
        $pricing2 = Functions::getContent($page['pricing']??'', []);
        
        if (Functions::testVar($pricing2)) {
            $usePricings = true;
        } else {
            $usePricings = false;
        }

        //dd($page);

    @endphp

    @component('lib.themewagon.article-sm')
        @foreach ($page['article'] as $key => $item)
            @if ($key === 'img' || is_array($item) || is_object($item))
                @php
                    //dd($key, $item);
                    //dd(serialize($item));
                @endphp
                @slot($key)
                    {!! serialize($item) !!}
                @endslot
            @else
                @slot($key)
                    {!! $item !!}
                @endslot
            @endif
        @endforeach
    @endcomponent

    @if (false)
        
        
    @else
        @php
            //dd('in' . __FILE__, 'content.index') ;
        @endphp
        {{-- Use the old new_products component --}}
        @component('lib.themewagon.new_products')
            @slot('sidebar')
                {!! $sidebar2 !!}
            @endslot
            @slot('newProducts')
                {!! $newProducts2 !!}
            @endslot
            @slot('sampleProducts')
                {!! $sampleProducts2 !!}
            @endslot
            @slot('newProductsTitle')
                {!! $newProductsTitle2 !!}
            @endslot
            @slot('sampleProductsTitle')
                {!! $sampleProductsTitle2 !!}
            @endslot
            @slot('currency')
                {!! $currency2 !!}
            @endslot
            @slot('filters')
                {!! $filters2 !!}
            @endslot
            @slot('bestsellers')
                {!! $bestsellers2 !!}
            @endslot
        @endcomponent

    @endif

    @if ($usePricings && false)
        @php
            //dd('ggg');
        @endphp
        @component('lib.themewagon.article')
            @foreach ($page['article'] as $key => $item)
                @slot($key)
                    {{ $item }}
                @endslot
            @endforeach
        @endcomponent

        @component('lib.bootstrapmade.pricing')
            @if(Functions::testVar($pricing2))

                @foreach ($pricing2 as $key => $item)
                    @slot($key)
                        {{$item}}
                    @endslot    
                @endforeach
            
            @endif
        @endcomponent

    
    @endif

    
@endsection

@section('js-defered')
    @parent

@endsection



