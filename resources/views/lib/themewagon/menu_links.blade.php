
@php
/*
 * A template for a 'normal-link' or a 'modal-link' menu-item
 * .. and nothing else!
 * 
 * $templateItem = [
  'icon' => '', // the Font Awesome 4 icon class without the 'fa'.
  'name' => '', // the name to fill in the Link.
  'url' => '', // the URL of the link.
  'type' => 'url', // 'url' for a url link, 'modal' for a modal button link..  
  'target' => '', // the data-target attribute's data value (of a modal)
  'transform' => '', // Bootstrap 3 text-transform css class.
  'cssExtraClasses' => '' // extra CSS classes for the a tag..
  ];
 * 
*/
 
 $testing = false;
 use App\Utilities\Functions\Functions,
    \App\Utilities\IterationStack\IterationStack,
    \App\Utilities\IterationStack\IterationFrame;
 
 $type2 = Functions::getBladedString($type??'','url');
 $target2 = Functions::getBladedString($target??'','');
 $cssExtraClasses2 = Functions::getBladedString($cssExtraClasses??'','');
 $url2 = Functions::getBladedString($url??'','#');
 $icon2 = Functions::getBladedString($icon??'','');
 $iconAfter2 = Functions::getBladedString($iconAfter??'','');
 $name2 = Functions::getBladedString($name??'','');
 $transform2 = Functions::getBladedString($transform??'','');

 if ($type2 == 'dropdown') {
    $submenus2 = Functions::getUnBladedContent($submenus??'','');
    $listCSS2 = "dropdown " . Functions::getBladedString($listCSS??'','');
    $toggle2 = Functions::getBladedString($toggle??'dropdown','dropdown');
    $role2 = Functions::getBladedString($role??'button','button');
 } else {
    $submenus2 = Functions::getUnBladedContent($submenus??'','');
    $toggle2 = Functions::getBladedString($toggle??'','');
    $role2 = Functions::getBladedString($role??'','');
    $listCSS2 = Functions::getBladedString($listCSS??'','');
 }
 

@endphp

{{-- begin menu top-level link --}}
<li
@if (Functions::testVar($listCSS2))
    class="{!! $listCSS2 !!}"
@endif
>

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

    
    @if (Functions::testVar($submenus2))

        <!-- BEGIN DROPDOWN MENU -->
        <ul class="dropdown-menu">

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

                    if ($elem_type === 'url'|| $elem_type === 'modal') {
                        $elem_listCssClass = ''
                    } elseif ($elem_type === 'dropdown-submenu' || $elem_type === 'dropdown') {
                        if ($elem->parent() !== null) {
                            $parent_type = $elem->parent()->get('type');
                            if (($parent_type == 'dropdown')||($parent_type == 'dropdown-submenu')) {
                                $elem_listCssClass = 'dropdown-submenu';
                            } else {
                                $elem_listCssClass = 'dropdown';
                            }
                        } else {
                            $elem_listCssClass = 'dropdown';
                        }
                    } 
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
                    {{-- the 'render' loop --}}
                
                @if ($elem_type === 'dropdown-submenu' || $elem_type === 'dropdown')
                    <!-- BEGIN DROPDOWN sub-MENU -->
                @endif

                <li
                    @if (Functions::testVar($elem_listCssClass))
                        class="{!! $elem_listCssClass !!}"
                    @endif
                    >

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

                
                    
                    
                    @if($elem_type === 'dropdown-submenu' || $elem_type === 'dropdown')
                        
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
                            now we close the inner list .. 
                        --}}
                            </ul>
                    @endif

                    {{-- AND.. close the list element.. --}}
                    
                </li>
                
                @if ($elem_type === 'dropdown-submenu' || $elem_type === 'dropdown')
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

        </ul>
        <!-- END DROPDOWN MENU -->
        
    @endif

</li>
{{-- end menu top-level link --}}