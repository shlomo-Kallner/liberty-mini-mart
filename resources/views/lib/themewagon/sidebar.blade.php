
@php

    $testing = true;
    $fakeData = '123FAKEDATA321';

    use \App\Utilities\Functions\Functions,
        \App\Utilities\IterationStack\IterationStack,
        \App\Utilities\IterationStack\IterationFrame;

    //dd($testing);
    $sidebar2 = Functions::getUnBladedContent($sidebar??'');
    //dd($sidebar2);
    $filters2 = Functions::getUnBladedContent($filters??$fakeData);
    //dd($filters2);
    $bestsellers2 = Functions::getUnBladedContent($bestsellers??$fakeData);
    //dd($bestsellers2);
    $currency2 = Functions::getBladedContent($currency??'fa-usd');
    //dd($currency2);
@endphp


<!-- BEGIN SIDEBAR -->
<div class="sidebar col-md-3 col-sm-5">

    @if (true)

        @component('lib.themewagon.sidebar_menu')
            @slot('sidebar')
                {!! Functions::getContent($sidebar??$fakeData) !!}
            @endslot
        @endcomponent

    @else
        
        <ul class="list-group margin-bottom-25 sidebar-menu">

            @if(Functions::testVar($sidebar2) || !$testing)
                
                @php
                
    
                    $frameStack = new IterationStack($sidebar2);
                    //dd($frameStack);
                    /*
                        $currentParent = null;
                        $currentFrame = [
                            'parent' => null,
                            'index'=> 0,
                            'children' => &$sidebar2['sidebar'],
                            'elem' => &$sidebar2['sidebar'][0],
                        ];
                        $siblingsArr = $sidebar2['sidebar'];
                        $currentIndex = 0;
                    */
                    $bol = true;
                @endphp
                
                {{-- 
                    Because of some REALLY COMPLICATED RECURSION DETAILS 
                    CANNOT use a @ForEach (YIKES!!!) ...
                    EMULATING instead (YIKE! YIKES!! YIKES!!!)...
                --}}
                
                @while($bol)
                    @php
                        /*
                            // setting/getting the current element..
                            $currentFrame['elem'] = &$currentFrame['elem'][$currentFrame['index']]
                            //$index = &$currentFrame['index'];
                        */
                        $elem = $frameStack->current();
                        $elem_type = $elem->get('type');
                        //dd($frameStack);
                        //dd($elem);
                        //dd($elem_type);
                    
                    @endphp
                        {{-- the 'render' loop --}}
                            
                    @if($elem_type === 'url')
    
                        @php
                            /* 'active' link or not is a ADVANCED+ Task LIst Item! 
                                -> Not IMPLEMENTNED YET!
                                @if($sidebar2['active']['url'] == $elem['url'])
                                <li class="list-group-item clearfix active">
                                @else
                                <li class="list-group-item clearfix">
                                @endif
                            */
                        @endphp
    
                        <li class="list-group-item clearfix">
                            <a href="{{ url($elem->get('url')) }}">
                                <i class="fa fa-angle-right"></i> {{$elem->get('name')}}
                            </a>
                        </li>
    
                    @elseif($elem_type === 'dropdown')
                        {{-- REMOVED: " && $elem->has('submenu') "! --}}
                        @php
                            /* -- the old code.. here for future use ..
    
                                @if($sidebar2['active']['url'] == $elem->get('url'))
                                    <li class="list-group-item clearfix dropdown active">
                                @else
                                    <li class="list-group-item clearfix dropdown">
                                @endif
                                @if($elem['data']['status'] === 'open' )
                                    <a href="javascript:void(0);" class="collapsed">
                                @else
                                    <a href="javascript:void(0);" class="">
                                @endif
    
                            */
                            //dd($frameStack);
                        @endphp
                    
                    
    
                        <li class="list-group-item clearfix dropdown">
                            <a href="javascript:void(0);" class="">
                                <i class="fa fa-angle-right"></i> {{$elem->get('name')}}
                            </a>
                            <ul class="dropdown-menu" style="display:block;">
                        {{-- Stack PUSH time! --}}
                        @php
                            $frameStack->push('submenu');
                            
                            /* -- the old code.. here for future use ..
                                $parentFrameStack[] = $currentFrame;
                                $currentFrame;
                                // setting the parent..
                                $currentFrame['parent'] = &$currentFrame['elem'][$currentFrame['index']];
                                // setting the children..
                                $currentFrame['children'] = &$elem['submenu'];
    
                            */
                        @endphp
                        @continue
                    @endif
    
                    {{-- 
                        Incrementing the index and 
                        check for end of frame.. 
                        IN ONE CHAINED SET OF CALLS!
                    --}}
                    @if ($frameStack->inc()->eof())
                        {{-- stack pop time! --}}
                        @php
                            $frameStack->pop()->inc();
                            // need to increment because pop just 
                            //  popped the frame, but left us in the
                            //  parent element..
                            // no worries over poping
                            // out of the 'top/first frame' 
                            // (we were "pushing" down/in/onto-the-end..)
                            // these methods do nothing if so..
    
                            /*  -- the old code.. here for future use ..
                                $tmp = array_pop($parentFrameStack);
                            */
                        @endphp
                        {{-- 
                            we have popped the stack frame, 
                            now we close both the inner list 
                            AND the outer list element.. 
                        --}}
                            </ul>
                        </li>
                    @endif
                    {{-- 
                        Could not chain empty() above 
                            due to the closing html tags above.. 
                    --}}
                    @php
                        //dd($frameStack);
                    @endphp
                    
                    @if ($frameStack->empty())
                        @php
                            $bol = false;
                        @endphp
                        @break
                    @endif
    
                @endwhile
            
            @else
    
                <li class="list-group-item clearfix">
                    <a href="lib/themewagon/metronicShopUI/theme/shop-product-list.html"><i class="fa fa-angle-right"></i> Ladies</a>
                </li>
                <li class="list-group-item clearfix dropdown active">
                    <a href="javascript:void(0);" class="collapsed">
                        <i class="fa fa-angle-right"></i>
                        Mens
                        
                    </a>
                    <ul class="dropdown-menu" style="display:block;">
                        <li class="list-group-item dropdown clearfix active">
                        <a href="javascript:void(0);" class="collapsed"><i class="fa fa-angle-right"></i> Shoes </a>
                            <ul class="dropdown-menu" style="display:block;">
                            <li class="list-group-item dropdown clearfix">
                                <a href="javascript:void(0);"><i class="fa fa-angle-right"></i> Classic </a>
                                <ul class="dropdown-menu">
                                <li><a href="shop-product-list.html"><i class="fa fa-angle-right"></i> Classic 1</a></li>
                                <li><a href="shop-product-list.html"><i class="fa fa-angle-right"></i> Classic 2</a></li>
                                </ul>
                            </li>
                            <li class="list-group-item dropdown clearfix active">
                                <a href="javascript:void(0);" class="collapsed"><i class="fa fa-angle-right"></i> Sport  </a>
                                <ul class="dropdown-menu" style="display:block;">
                                <li class="active"><a href="shop-product-list.html"><i class="fa fa-angle-right"></i> Sport 1</a></li>
                                <li><a href="shop-product-list.html"><i class="fa fa-angle-right"></i> Sport 2</a></li>
                                </ul>
                            </li>
                            </ul>
                        </li>
                        <li><a href="shop-product-list.html"><i class="fa fa-angle-right"></i> Trainers</a></li>
                        <li><a href="shop-product-list.html"><i class="fa fa-angle-right"></i> Jeans</a></li>
                        <li><a href="shop-product-list.html"><i class="fa fa-angle-right"></i> Chinos</a></li>
                        <li><a href="shop-product-list.html"><i class="fa fa-angle-right"></i> T-Shirts</a></li>
                    </ul>
                </li>
                <li class="list-group-item clearfix">
                    <a href="lib/themewagon/metronicShopUI/theme/shop-product-list.html"><i class="fa fa-angle-right"></i> Kids</a>
                </li>
                <li class="list-group-item clearfix">
                    <a href="lib/themewagon/metronicShopUI/theme/shop-product-list.html"><i class="fa fa-angle-right"></i> Accessories</a>
                </li>
                <li class="list-group-item clearfix">
                    <a href="lib/themewagon/metronicShopUI/theme/shop-product-list.html"><i class="fa fa-angle-right"></i> Sports</a>
                </li>
                <li class="list-group-item clearfix">
                    <a href="lib/themewagon/metronicShopUI/theme/shop-product-list.html"><i class="fa fa-angle-right"></i> Brands</a>
                </li>
                <li class="list-group-item clearfix">
                    <a href="lib/themewagon/metronicShopUI/theme/shop-product-list.html"><i class="fa fa-angle-right"></i> Electronics</a>
                </li>
                <li class="list-group-item clearfix">
                    <a href="lib/themewagon/metronicShopUI/theme/shop-product-list.html"><i class="fa fa-angle-right"></i> Home & Garden</a>
                </li>
                <li class="list-group-item clearfix">
                    <a href="lib/themewagon/metronicShopUI/theme/shop-product-list.html"><i class="fa fa-angle-right"></i> Custom Link</a>
                </li>
    
            @endif
    
        </ul>

    @endif

    
    @if (false)
        @component('lib.themewagon.sidebar_filters')
            @slot('filters')
                {!! Functions::getContent($filters??$fakeData) !!}
            @endslot
            @slot('currency')
                {!! Functions::getContent($currency??'fa-usd') !!}
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


