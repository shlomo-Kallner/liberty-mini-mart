
@php

    $testing = true;
    $fakeData = '123FAKEDATA321';

    use \App\Utilities\Functions\Functions,
        \App\Utilities\IterationStack\IterationStack,
        \App\Utilities\IterationStack\IterationFrame;

    //dd($testing);
    $sidebar2 = Functions::getContent($sidebar??'');
    //dd($sidebar2);
    if (!$testing) {
        $filters2 = Functions::getContent($filters??$fakeData);  
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
    }
    //dd($filters2);
    $bestsellers2 = Functions::getUnBladedContent($bestsellers??$fakeData);
    //dd($bestsellers2);
    $currency2 = Functions::getContent($currency??'fa-usd');
    //dd($currency2);
@endphp


<!-- BEGIN SIDEBAR -->
<div class="sidebar col-md-3 col-sm-5">

    @component('lib.themewagon.sidebar_menu')
        @slot('sidebar')
            {!! $sidebar2 !!}
        @endslot
    @endcomponent

    
    
    @if (true)
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
    @else
        
        @if(Functions::testVar($filters2) || $testing)
            <div class="sidebar-filter margin-bottom-25">
                <h2>Filters</h2>

                @if (false)
                    @foreach ($filters2 as $item)

                        <h3>{{ $item['name'] }}</h3>
                        {!! $item['filter'] !!}
                        
                    @endforeach
            
                @else

                    <h3>Availability</h3>
                    <div class="checkbox-list">
                        <label><input type="checkbox"> Not Available (3)</label>
                        <label><input type="checkbox"> In Stock (26)</label>
                    </div>

                    <h3>Price</h3>
                    <p>
                        <label for="amount">Range:</label>
                        <input type="text" id="amount" style="border:0; color:#f6931f; font-weight:bold;">
                    </p>
                    <div id="slider-range"></div>
                    
                @endif
                
            </div>
        @endif

    @endif
    
    @if (false)
        @component('lib.themewagon.sidebar_products')
            @slot('products')
                {!! Functions::getContent($bestsellers??$fakeData) !!}
            @endslot
            @slot('currency')
                {!! Functions::getContent($currency??'fa-usd') !!}
            @endslot
            @slot('title')
                {!! "Bestsellers" !!}
            @endslot
        @endcomponent
    @else
        
        @if(Functions::testVar($bestsellers2) || $testing)

            <div class="sidebar-products clearfix">
                <h2>Bestsellers</h2>

                @if (false)

                    @foreach ($bestsellers2 as $item)

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

    @endif
        
    
    

</div>
<!-- END SIDEBAR -->


