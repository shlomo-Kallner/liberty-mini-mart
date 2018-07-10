

{{-- NOTE: this is a ADVANCED-TASK COMPONENT! 
    Not to be done until the main project is completed! --}}
@php

$testing = false;
use \App\Utilities\Functions\Functions,
    \App\Utilities\IterationStack\IterationStack,
    \App\Utilities\IterationStack\IterationFrame;

// BEGIN: the slots of the external dropdown element

$listCSS2 = Functions::getBladedString($listCSS??'dropdown','dropdown');
 
$type2 = Functions::getBladedString($type??'','dropdown');



$target2 = Functions::getBladedString($target??'','#');
$cssExtraClasses2 = Functions::getBladedString($cssExtraClasses??'','');
$url2 = Functions::getBladedString($url??'','javascript:void(0);');
$icon2 = Functions::getBladedString($icon??'','');
$iconAfter2 = Functions::getBladedString($iconAfter??'','');
$name2 = Functions::getBladedString($name??'','');
$transform2 = Functions::getBladedString($transform??'','');
$submenus2 = Functions::getUnBladedContent($submenus??'','');
$toggle2 = Functions::getBladedString($toggle??'dropdown','dropdown');
$role2 = Functions::getBladedString($role??'button','button');

// END: the slots of the external dropdown element

// BEGIN: Dropdown IterationStack Utilities

// END: Dropdown IterationStack Utilities

@endphp

{{-- begin dropdown menu top-level link --}}
<li class="dropdown {!! $listCSS2 !!}">

    @if (true)

        @component('lib.themewagon.links')
            @slot('type')
                {!! $type2 !!}
            @endslot
            @slot('target')
                {!! $target2 !!}
            @endslot
            @slot('cssExtraClasses')
                {!! $cssExtraClasses2 !!}
            @endslot
            @slot('url')
                {!! $url2 !!}
            @endslot
            @slot('icon')
                {!! $icon2 !!}
            @endslot
            @slot('iconAfter')
                {!! $iconAfter2 !!}
            @endslot
            @slot('name')
                {!! $name2 !!}
            @endslot
            @slot('transform')
                {!! $transform2 !!}
            @endslot
            @slot('toggle')
                {!! $toggle2 !!}
            @endslot
            @slot('role')
                {!! $role2 !!}
            @endslot
        @endcomponent

    @else
        
        <a class="dropdown-toggle {{ $cssExtraClasses2 }}" 
            data-toggle="{{ $toggle2 }}" data-target="{{ $target2 }}" 
            href="{{ url($url2) }}" role="{{$role2}}"
            >
        
            @if( Functions::testVar($icon2) && (mb_strlen($icon2) !== 0) )
                @if ( !Functions::testVar($name2) || (mb_strlen($name2) === 0)  )
                <i class="fa {{ $icon2 }}"></i>    
                @else
                <i class="fa {{ $icon2 }}" aria-hidden="true"></i>
                @endif
            @endif
    
            @if ( Functions::testVar($name2) && (mb_strlen($name2) !== 0)  ) 
                @if(mb_strlen($transform2) !== 0)
                    <span class="hidden-xs {{ $transform2 }}">{{ $name2 }}</span> 
                @else 
                    {{ $name2 }} 
                @endif 
            @endif
    
            @if( Functions::testVar($iconAfter2) && (mb_strlen($iconAfter2) !== 0) )
                <i class="fa {{ $iconAfter2 }}"></i>
            @endif
    
        </a>

    @endif

    
    @if (Functions::testVar($submenus2))

        <!-- BEGIN DROPDOWN MENU -->
        <ul class="dropdown-menu">


            @if (true)
                {{-- copying outright from sidebar_menu.blade.php.. --}}
                
                @php
                    // copying outright from sidebar_menu.blade.php..

                    $frameStack = new IterationStack($submenus2);
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

                @while ($bol)
                    
                    @php

                        // setting/getting the current element..
                        $elem = $frameStack->current();
                        $elem_type = $elem->get('type');
                        //dd($frameStack);
                        //dd($elem);
                        //dd($elem_type);

                    @endphp
                        {{-- the 'render' loop --}}
                    
                    @if ($elem_type === 'url'|| $elem_type === 'modal')

                        @component('lib.themewagon.menu_links')
                            @foreach ($elem->current() as $key => $value)

                                @slot($key)
                                    {{ $value }}
                                @endslot
                                    
                            @endforeach
                        @endcomponent
                        
                    @elseif($elem_type === 'dropdown-submenu' || $elem_type === 'dropdown')
                    @php

                        if ($elem->parent() !== null) {
                            $parent_type = $elem->parent()->get('type');
                            if (($parent_type == 'dropdown')||($parent_type == 'dropdown-submenu')) {
                                $listCssClass = 'dropdown-submenu';
                            } else {
                                $listCssClass = 'dropdown';
                            }
                        } else {
                            $listCssClass = 'dropdown';
                        }
                            
                    @endphp

                        <!-- BEGIN DROPDOWN sub-MENU -->
                        <li class="dropdown-submenu">
                            @php
                                $elem_cssExtraClasses = $elem->get('cssExtraClasses');
                                $elem_target = $elem->get('target');
                                $elem_url = $elem->get('url');
                                $elem_icon = $elem->get('icon');
                                $elem_name = $elem->get('name');
                                $elem_transform = $elem->get('transform');
                                $elem_iconAfter = $elem->get('iconAfter');
                                $elem_toggle = $elem->get('toggle');
                                $elem_role = $elem->get('role');
                            @endphp

                            @if (true)

                                @component('lib.themewagon.links')
                                    @slot('type')
                                        {!! $elem_type !!}
                                    @endslot
                                    @slot('target')
                                        {!! $elem_target !!}
                                    @endslot
                                    @slot('cssExtraClasses')
                                        {!! $elem_cssExtraClasses !!}
                                    @endslot
                                    @slot('url')
                                        {!! $elem_url !!}
                                    @endslot
                                    @slot('icon')
                                        {!! $elem_icon !!}
                                    @endslot
                                    @slot('iconAfter')
                                        {!! $elem_iconAfter !!}
                                    @endslot
                                    @slot('name')
                                        {!! $elem_name !!}
                                    @endslot
                                    @slot('transform')
                                        {!! $elem_transform !!}
                                    @endslot
                                    @slot('toggle')
                                        {!! $elem_toggle !!}
                                    @endslot
                                    @slot('role')
                                        {!! $elem_role !!}
                                    @endslot
                                @endcomponent
                                
                            @else
                                
                                <a class="dropdown-toggle {{ $elem_cssExtraClasses }}" data-toggle="{{ $elem_toggle }}" 
                                    data-target="{{ $elem_target }}" href="{{ url($elem_url) }}" role="{{ $elem_role }}">

                                    @if( Functions::testVar($elem_icon) && (mb_strlen($elem_icon) !== 0) )
                                        @if ( !Functions::testVar($elem_name) || (mb_strlen($elem_name) === 0)  )
                                        <i class="fa {{ $elem_icon }}"></i>    
                                        @else
                                        <i class="fa {{ $elem_icon }}" aria-hidden="true"></i>
                                        @endif
                                    @endif
                            
                                    @if ( Functions::testVar($elem_name) && (mb_strlen($elem_name) !== 0)  ) 
                                        @if(mb_strlen($elem_transform) !== 0)
                                            <span class="hidden-xs {{ $elem_transform }}">{{ $elem_name }}</span> 
                                        @else 
                                            {{ $elem_name }} 
                                        @endif 
                                    @endif
                            
                                    @if( Functions::testVar($elem_iconAfter) && (mb_strlen($elem_iconAfter) !== 0) )
                                        <i class="fa {{ $elem_iconAfter }}"></i>
                                    @endif
                        
                                </a>

                            @endif

                            <ul class="dropdown-menu" role="menu">
                            {{-- Stack PUSH time! --}}
                            @php
                                $frameStack->push('submenu');
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
                        <!-- END DROPDOWN sub-MENU -->
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
                {{-- our old code.. --}}
                
                @foreach($submenus2 as $nav)

                    @if( $nav['type'] == 'url' || $nav['type'] == 'modal' )

                        @component('lib.themewagon.menu_links')
                            @foreach ($nav as $key => $value)

                                @slot($key)
                                    {{ $value }}
                                @endslot
                                    
                            @endforeach
                        @endcomponent

                    @elseif($nav['type'] == 'dropdown-submenu' || $nav['type'] == 'dropdown')
                        {{-- Begin submenu.. --}}
                        
                        {{-- DROPDOWN-SUBMENUS ARE A WISHLIST-TASK COMPONENT -> NOT IMPLEMENTED YET! --}}

                        @if (false)

                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="javascript:;">
                                    Woman 

                                </a>

                                <!-- BEGIN DROPDOWN MENU -->
                                <ul class="dropdown-menu">
                                    <li class="dropdown-submenu">
                                        <a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Hi Tops <i class="fa fa-angle-right"></i></a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Second Level Link</a></li>
                                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Second Level Link</a></li>
                                            <li class="dropdown-submenu">
                                                <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="javascript:;">
                                                    Second Level Link 
                                                    <i class="fa fa-angle-right"></i>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Third Level Link</a></li>
                                                    <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Third Level Link</a></li>
                                                    <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Third Level Link</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                    <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Running Shoes</a></li>
                                    <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Jackets and Coats</a></li>
                                </ul>
                                <!-- END DROPDOWN MENU -->
                            </li>
                        
                        @endif
                        
                        {{-- End submenu.. --}}

                    @endif
                    
                @endforeach

            @endif

        </ul>
        <!-- END DROPDOWN MENU -->
        
    @endif

</li>
{{-- end dropdown menu top-level link --}}

