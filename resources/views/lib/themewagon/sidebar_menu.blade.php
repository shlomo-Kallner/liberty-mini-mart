

@php
    $testing = false;
    use \App\Utilities\Functions\Functions,
        \App\Utilities\IterationStack\IterationStack,
        \App\Utilities\IterationStack\IterationFrame;

    //dd($testing);
    //dd($menu);
    $menu2 = Functions::getUnBladedContent($menu??'');
    //dd($menu2);

@endphp


    <ul class="list-group margin-bottom-25 sidebar-menu">

        @if(Functions::testVar($menu2))
            
            @php
            

                $frameStack = new IterationStack($menu2);
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

                    @component('lib.themewagon.menu_links')
                        @slot('listCSS')
                            {!! "list-group-item clearfix" !!}
                        @endslot
                        @slot('type')
                            {!! "url" !!}
                        @endslot
                        @slot('url')
                            {{ url($elem->get('url')) }}
                        @endslot
                        @slot('name')
                            {{$elem->get('name')}} 
                        @endslot
                        @slot('icon')
                            {!! "fa-angle-right" !!}
                        @endslot
                    @endcomponent

                    @php
                        // the old code: here for reference.. 
                        
                        /*
                            <li class="list-group-item clearfix">
                                <a href="{{ url($elem->get('url')) }}">
                                    <i class="fa fa-angle-right"></i> {{$elem->get('name')}}
                                </a>
                            </li>
                        */
                    @endphp
                    
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
                    @endphp

                    <li class="list-group-item clearfix dropdown">
                        <a href="javascript:void(0);" class="">
                            <i class="fa fa-angle-right"></i> {{$elem->get('name')}}
                        </a>
                        <ul class="dropdown-menu" style="display:none;">
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
                
                @if ($frameStack->empty())
                    @php
                        $bol = false;
                    @endphp
                    @break
                @endif

            @endwhile
        
        @elseif($testing)

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