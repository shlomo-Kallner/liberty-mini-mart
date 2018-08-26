

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
        }
        if (!$testing) {
            $sampleProducts2 = serialize(Functions::getContent($page['sampleProducts']['products']??''));
        } else {
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
        $sidebar2 = serialize(Functions::getContent($sidebar??''));
        $pricing2 = Functions::getContent($pricing??'');
        $currency2 = Functions::getContent($currency??'','fa-usd');
        $filters2 = Functions::getContent($filters2??'');
        $bestsellers2 = serialize(Functions::getContent($page['bestsellers']??''));

        if (Functions::testVar($pricing2)) {
            $usePricings = true;
        } else {
            $usePricings = false;
        }

        //dd($page);

    @endphp

    @component('lib.themewagon.article-sm')
        @foreach ($page['article'] as $key => $item)
            @if ($key === 'img')
                @php
                    //dd($item);
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
                {!! "New Arrivals" !!}
            @endslot
            @slot('sampleProductsTitle')
                {!! "Three Items" !!}
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

    @if ($usePricings)
    
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



