

@php
    

    $testing = true;
    use \App\Utilities\Functions\Functions,
        \App\Utilities\IterationStack\IterationStack,
        \App\Utilities\IterationStack\IterationFrame,
        \App\Page;

    if ($testing) {
        /*
            Page::genDropdownLink(
                string $name, array $submenus = [], string $icon = '', 
                string $textTransform = '', string $cssExtraClasses = '',
                string $url = 'javascript:void(0);', string $iconAfter = 'fa-angle-right'
            )
            Page::genURLMenuItem(
                string $url, string $name, string $icon = '', 
                string $textTransform = '', string $cssExtraClasses = '', 
                string $iconAfter = ''
            )
            Page::genModalMenuItem(
                string $name, string $target, string $icon = '', 
                string $textTransform = '', string $cssExtraClasses = '', 
                string $iconAfter = ''
            )
        */
        $menu2 = [
            
            Page::genURLMenuItem('admin','Dashboard', 'fa-dashboard'),
            Page::genDropdownLink('Charts', [
                Page::genURLMenuItem('/lib/startbootstrap/admin2/pages/flot.html','Flot Charts'),
                Page::genURLMenuItem('/lib/startbootstrap/admin2/pages/morris.html','Morris.js Charts'),
            ], 'fa-bar-chart-o', '', '', '#', 'arrow'),
            Page::genURLMenuItem('/lib/startbootstrap/admin2/pages/tables.html','Tables', 'fa-table'),
            Page::genURLMenuItem('/lib/startbootstrap/admin2/pages/forms.html','Forms', 'fa-edit'),
            Page::genDropdownLink('UI Elements',[
                Page::genURLMenuItem('/lib/startbootstrap/admin2/pages/panels-wells.html','Panels and Wells'),
                Page::genURLMenuItem('/lib/startbootstrap/admin2/pages/buttons.html','Buttons'),
                Page::genURLMenuItem('/lib/startbootstrap/admin2/pages/notifications.html','Notifications'),
                Page::genURLMenuItem('/lib/startbootstrap/admin2/pages/typography.html','Typography'),
                Page::genURLMenuItem('/lib/startbootstrap/admin2/pages/icons.html','Icons'),
                Page::genURLMenuItem('/lib/startbootstrap/admin2/pages/grid.html','Grid'),
            ],'fa-wrench', '', '', '#', 'arrow'),
            Page::genDropdownLink('Multi-Level Dropdown', [
                Page::genURLMenuItem('#','Second Level Item'),
                Page::genURLMenuItem('#','Second Level Item'),
                Page::genDropdownLink('Third Level', [
                    Page::genURLMenuItem('#','Third Level Item'),
                    Page::genURLMenuItem('#','Third Level Item'),
                    Page::genURLMenuItem('#','Third Level Item'),
                ], '', '', '', '#', 'arrow'),
            ], 'fa-sitemap', '', '', '#', 'arrow'),
            Page::genDropdownLink('Sample Pages', [
                Page::genURLMenuItem('/lib/startbootstrap/admin2/pages/blank.html','Blank Page'),
                Page::genURLMenuItem('/lib/startbootstrap/admin2/pages/login.html','Login Page'),
            ], 'fa-files-o', '', '', '#', 'arrow')
            
        ];
        //serialize(Page::getSidebar(true));
    } else {
        $menu2 = Functions::getUnBladedContent($menu??'');
    }

@endphp

    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                @if (false)
                    <li class="sidebar-search">
                        <div class="input-group custom-search-form">
                            <input type="text" class="form-control" placeholder="Search...">
                            <span class="input-group-btn">
                            <button class="btn btn-default" type="button">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                        </div>
                        <!-- /input-group -->
                    </li>        
                @endif
                @php
                    //dd($menu2);
                @endphp
                @if (Functions::testVar($menu2))
                        
                        @php
                            $frameStack = new IterationStack($menu2);
                            $bol = true;
                        @endphp

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
                                    @endphp
        
                                    <a class="dropdown-toggle {{ $elem_cssExtraClasses }}" data-toggle="dropdown" 
                                        data-target="{{ $elem_target }}" href="{{ url($elem_url) }}" role="button">
        
                                        @if( Functions::testVar($elem_icon) && (mb_strlen($elem_icon) !== 0) )
                                            @if ( !Functions::testVar($elem_name) || (mb_strlen($elem_name) === 0)  )
                                                <i class="fa {{ $elem_icon }} fa-fw"></i>    
                                            @else
                                                <i class="fa {{ $elem_icon }} fa-fw" aria-hidden="true"></i>
                                            @endif
                                        @endif
                                
                                        @if ( Functions::testVar($elem_name) && (mb_strlen($elem_name) !== 0)  ) 
                                            @if(mb_strlen($elem_transform) !== 0)
                                                <span class="hidden-xs {{ $elem_transform }}">{{ $elem_name }}</span> 
                                            @else 
                                                {{ $elem_name }} 
                                            @endif 
                                        @endif
                                
                                        {{-- this should be 'arrow' 
                                            (see '.arrow' in 'sb-admin-2.css') .. --}}
                                        @if( Functions::testVar($elem_iconAfter) && (mb_strlen($elem_iconAfter) !== 0) )
                                            <i class="fa {{ $elem_iconAfter }}"></i>
                                        @endif
                            
                                    </a>
                                    <ul class="nav dropdown-menu" role="menu">
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

                @endif
            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->