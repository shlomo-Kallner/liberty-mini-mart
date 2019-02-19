
@php

    /*
     * A template for a 'normal-link' or a 'modal-link' menu-item
     * .. and nothing else! (except dropdown-links..)
     * 
     *   $templateItem = [
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
    $controls2 = Functions::getBladedString($controls??'','');
    $submenus2 = Functions::getUnBladedContent($submenus??'','');

    if ($type2 == 'dropdown') {
        $lc = Functions::getBladedString($listCSS??'','');
        $listCSS2 = Functions::testVar($lc) ? "dropdown " . $lc : "dropdown" ;
        $toggle2 = Functions::getBladedString($toggle??'dropdown','dropdown');
        $role2 = Functions::getBladedString($role??'button','button');
    } else {
        $toggle2 = Functions::getBladedString($toggle??'','');
        $role2 = Functions::getBladedString($role??'','');
        $listCSS2 = Functions::getBladedString($listCSS??'','');
    }
 
    //dd($submenus2);

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
        @slot('controls')
            {!! $controls2 !!}
        @endslot
    @endcomponent

    
    @if (Functions::testVar($submenus2))

        <!-- BEGIN DROPDOWN MENU -->
        <ul class="dropdown-menu">

            {{-- copying outright from sidebar_menu.blade.php.. --}}
            
            @php
                // copying outright from sidebar_menu.blade.php..
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
                
                $frameStack = new IterationStack($submenus2);
                $bol = true;
                dd($submenus2, $frameStack);
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
                    $elem_parent = $elem->parent();
                    $elem_listCssClass = '';
                    //dd($frameStack);
                    //dd($elem, $elem->current());
                    dd($frameStack, $elem, $elem_type, $elem->current());

                    if ($elem_type === 'url' || $elem_type === 'modal') {
                        $elem_listCssClass = '';
                    } elseif ($elem_type === 'dropdown-submenu' || $elem_type === 'dropdown') {
                        if ($elem_parent !== null) {
                            $parent_type = $elem_parent->get('type');
                            if ($parent_type == 'dropdown' || $parent_type == 'dropdown-submenu') {
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
                    $elem_controls = $elem->get('controls');

                @endphp
                    {{-- the 'render' loop --}}
                
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
                        @slot('controls')
                            {!! $elem_controls !!}
                        @endslot
                    @endcomponent

                
                    @php
                        //dd($elem, $elem_type);
                    @endphp
                    
                    @if(($elem_type === 'dropdown-submenu' || $elem_type === 'dropdown')
                    && Functions::testVar($elem->get('submenus')))
                    
                        <!-- BEGIN DROPDOWN sub-MENU -->
                        <ul class="dropdown-menu" role="menu">
                        {{-- Stack PUSH time! --}}
                        @php
                            $frameStack->push('submenus');
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
                            // UNTRUE! UPDATE: REQUIRED TO CHECK IF WE 
                            // HAVE POPPED THE 'top/first frame'!
                            // WE GET EXTRA HTML CLOSE TAGS IF WE DO NOT!!!

                            /*  -- the old code.. here for future use ..
                                $tmp = array_pop($parentFrameStack);
                            */
                        @endphp
                        {{-- 
                            if we have popped the stack frame of a inner 
                            (not first!) frame, now we close the inner list .. 
                        --}}
                        @if (Functions::testVar($elem_parent))
                            {{-- @if ($elem_type !== 'dropdown-submenu' || $elem_type !== 'dropdown') --}}
                            
                                </li>
                            {{-- @endif --}}
                            
                            </ul>
                            <!-- END DROPDOWN sub-MENU -->
                        @endif
                    @endif

                    {{-- AND.. close the list element.. --}}
                    
                </li>
                
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