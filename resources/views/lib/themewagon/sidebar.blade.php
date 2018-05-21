
@php

    $testing = true;

    if(isset($sidebar) && unserialize(html_entity_decode((string)$sidebar)) !== ''){
        $sidebar2 = unserialize(html_entity_decode((string)$sidebar));
    }else{
        $sidebar2 = [];
    }
@endphp


<!-- BEGIN SIDEBAR -->
<div class="sidebar col-md-3 col-sm-5">

    @if(isset($sidebar2['sidebar']))
        <ul class="list-group margin-bottom-25 sidebar-menu">

            @if(!$testing)
                
                @php
                use \App\Utilities\IterationStack\IterationStack,
                    \App\Utilities\IterationStack\IterationFrame;

                    $frameStack = new IterationStack(&$sidebar2['sidebar']);
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
                    @endphp
                        {{-- the 'render' loop --}}
                            
                        @if($elem->get('type') === 'url')

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

                        @elseif($elem->get('type') === 'dropdown' && $elem->has('submenu'))
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
                            $frameStack->pop();
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
                    
                    @if ($frameStack->empty())
                        @php
                            $bol = false;
                        @endphp
                        @break
                    @endif

                @endwhile
            
            @else

                <li class="list-group-item clearfix"><a href="shop-product-list.html"><i class="fa fa-angle-right"></i> Ladies</a></li>
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
                <li class="list-group-item clearfix"><a href="shop-product-list.html"><i class="fa fa-angle-right"></i> Kids</a></li>
                <li class="list-group-item clearfix"><a href="shop-product-list.html"><i class="fa fa-angle-right"></i> Accessories</a></li>
                <li class="list-group-item clearfix"><a href="shop-product-list.html"><i class="fa fa-angle-right"></i> Sports</a></li>
                <li class="list-group-item clearfix"><a href="shop-product-list.html"><i class="fa fa-angle-right"></i> Brands</a></li>
                <li class="list-group-item clearfix"><a href="shop-product-list.html"><i class="fa fa-angle-right"></i> Electronics</a></li>
                <li class="list-group-item clearfix"><a href="shop-product-list.html"><i class="fa fa-angle-right"></i> Home & Garden</a></li>
                <li class="list-group-item clearfix"><a href="shop-product-list.html"><i class="fa fa-angle-right"></i> Custom Link</a></li>

            @endif

        </ul>
    @endif

    @if(isset($sidebar2['filters']) && !empty($sidebar2['filters']))
        <div class="sidebar-filter margin-bottom-25">
            <h2>Filter</h2>

            @if (false)
                @foreach ($sidebar2['filters'] as $item)

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

    @if(isset($sidebar2['bestsellers']) && !empty($sidebar2['bestsellers']))
        <div class="sidebar-products clearfix">
            <h2>Bestsellers</h2>
            @if (false)

                @foreach ($sidebar2['bestsellers'] as $item)

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
                            <i class="fa {{$currency}}"></i>
                            {{$item['price']}}
                        </div>
                    </div>
                    
                @endforeach
                    
            @else
                <div class="item">
                    <a href="shop-item.html">
                        <img src="assets/pages/img/products/k1.jpg" alt="Some Shoes in Animal with Cut Out">
                    </a>
                    <h3>
                        <a href="shop-item.html">Some Shoes in Animal with Cut Out</a>
                    </h3>
                    <div class="price">$31.00</div>
                </div>
                <div class="item">
                <a href="shop-item.html"><img src="assets/pages/img/products/k4.jpg" alt="Some Shoes in Animal with Cut Out"></a>
                <h3><a href="shop-item.html">Some Shoes in Animal with Cut Out</a></h3>
                <div class="price">$23.00</div>
                </div>
                <div class="item">
                    <a href="shop-item.html">
                        <img src="assets/pages/img/products/k3.jpg" alt="Some Shoes in Animal with Cut Out">
                    </a>
                    <h3>
                        <a href="shop-item.html">Some Shoes in Animal with Cut Out</a>
                    </h3>
                    <div class="price">$86.00</div>
                </div>
            @endif
        </div>
    @endif

</div>
<!-- END SIDEBAR -->


