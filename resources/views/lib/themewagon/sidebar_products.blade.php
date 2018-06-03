

@php

    $testing = false;
    //$fakeData = ''; // old fakeData '123FAKEDATA'
    use \App\Utilities\Functions\Functions;

    if (!$testing) {
        $products2 = Functions::getUnBladedContent($products??'','');
    } else {
        $products2 = [
            [
                'url' => 'lib/themewagon/metronicShopUI/theme/shop-item.html',
                'img' => 'lib/themewagon/metronicShopUI/theme/assets/pages/img/products/k1.jpg',
                'alt' => 'Some Shoes in Animal with Cut Out',
                'price' => '31.00',
            ],
            [
                'url' => 'lib/themewagon/metronicShopUI/theme/shop-item.html',
                'img' => 'lib/themewagon/metronicShopUI/theme/assets/pages/img/products/k4.jpg',
                'alt' => 'Some Shoes in Animal with Cut Out',
                'price' => '23.00',
            ],
            [
                'url' => 'lib/themewagon/metronicShopUI/theme/shop-item.html',
                'img' => 'lib/themewagon/metronicShopUI/theme/assets/pages/img/products/k3.jpg',
                'alt' => 'Some Shoes in Animal with Cut Out',
                'price' => '86.00',
            ],
        ];
    }
    
    //dd($products2);
    $title2 = Functions::getBladedString($title??'','');
    $currency2 = Functions::getBladedString($currency??'fa-usd','fa-usd');
    //dd($currency2);

@endphp

@if(Functions::testVar($products2))

    <div class="sidebar-products clearfix">
        <h2>{{ $title2 }}</h2>

        @if (Functions::testVar($products2))

            @foreach ($products2 as $item)

                <div class="item">
                    <a href="{{ url($item['url'])}}">
                        <img src="{{asset($item['img'])}}" alt="{{ $item['alt'] }}">
                    </a>
                    <h3>
                        <a href="{{asset($item['url'])}}">
                            {{ $item['alt'] }}
                        </a>
                    </h3>
                    <div class="price">
                        <i class="fa {{$currency2}}"></i>
                        {{$item['price']}}
                    </div>
                </div>
                
            @endforeach
                
        @endif

    </div>

@endif