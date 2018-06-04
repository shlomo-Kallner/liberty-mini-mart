
@extends('content.template')


@section('main-content')
    @parent

    {{-- OUR SLOTS... --}}
    @php
        $testing = true;
        $fakeData = '';

        use \App\Utilities\Functions\Functions,
            \App\Page;

        if (!$testing) {
            $sidebarMenu2 = serialize(Functions::getContent($sidebarMenu??$fakeData,$fakeData));
        } else {
            $sidebarMenu2 = serialize(Page::getSidebar(true));
        }
        if (!$testing) {
            $sidebarProducts2 = Functions::getContent($sidebarProducts??$fakeData,$fakeData);
        } else {
            $sidebarProducts2 = serialize([
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
        //dd($sidebarProducts2);
        $currency2 = Functions::getContent($currency??'fa-usd','fa-usd');

    @endphp

    <!-- BEGIN SIDEBAR & CONTENT -->
        <div class="row margin-bottom-40">
            @component('lib.themewagon.sidebar')
                @slot('menu')
                    {!! $sidebarMenu2 !!}
                @endslot
                {{-- ignoring the 'filters' slot to omit it.. --}}
                @slot('products')
                    {!! $sidebarProducts2 !!}
                @endslot
                @slot('currency')
                    {!! $currency2 !!}
                @endslot
            @endcomponent

            @component('lib.themewagon.product_full_item')
                
            @endcomponent

        </div>
    <!-- END SIDEBAR & CONTENT -->

@endsection

@section('css-preloaded')
    @parent 
    <!-- include summernote css -->
    <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet">
@endsection

@section('js-defered')
    @parent
    <!-- include summernote js -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script>
@endsection