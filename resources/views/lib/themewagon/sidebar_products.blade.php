

@php

    $testing = true;
    use \App\Utilities\Functions\Functions;

    $products2 = Functions::getUnBladedContent($products??'123FAKEDATA');
    //dd($bestsellers2);
    $title2 = Functions::getBladedContent($title??'');
    $currency2 = Functions::getBladedContent($currency??'fa-usd');
    //dd($currency2);

@endphp

@if(Functions::testVar($products2) || $testing)

    <div class="sidebar-products clearfix">
        <h2>{{ $title2 }}</h2>

        @if (false)

            @foreach ($products2 as $item)

                <div class="item">
                    <a href="{{ url($item['url'])}}">
                        <img src="{{asset($item['img'])}}" alt="{{ $item['alt'] }}">
                    </a>
                    <h3>
                        <a href="{{asset($item['img'])}}">
                            {{ $item['alt'] }}
                        </a>
                    </h3>
                    <div class="price">
                        <i class="fa {{$currency2}}"></i>
                        {{$item['price']}}
                    </div>
                </div>
                
            @endforeach
                
        @else
            <div class="item">
                <a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">
                    <img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/k1.jpg') }}" alt="Some Shoes in Animal with Cut Out">
                </a>
                <h3>
                    <a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Some Shoes in Animal with Cut Out</a>
                </h3>
                <div class="price">$31.00</div>
            </div>
            <div class="item">
                <a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">
                    <img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/k4.jpg') }}" alt="Some Shoes in Animal with Cut Out">
                </a>
                <h3><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Some Shoes in Animal with Cut Out</a></h3>
                <div class="price">$23.00</div>
            </div>
            <div class="item">
                <a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">
                    <img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/k3.jpg') }}" alt="Some Shoes in Animal with Cut Out">
                </a>
                <h3>
                    <a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Some Shoes in Animal with Cut Out</a>
                </h3>
                <div class="price">$86.00</div>
            </div>
        @endif
    </div>

@endif