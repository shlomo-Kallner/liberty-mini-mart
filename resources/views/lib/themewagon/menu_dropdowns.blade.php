

{{-- NOTE: this is a ADVANCED-TASK COMPONENT! 
    Not to be done until the main project is completed! --}}


{{-- begin dropdown menu top-level link --}}
<li class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="javascript:;" role="button">
        {{ $name }}

    </a>

    <!-- BEGIN DROPDOWN MENU -->
    <ul class="dropdown-menu">
        
        @foreach(unserialize($submenus) as $nav)

        @if( $nav['type'] == 'url' || $nav['type'] == 'modal' )

        @component('lib.themewagon.links')
            @foreach ($nav as $key => $value)

                @slot($key)
                    {{ $value }}
                @endslot
                    
            @endforeach
        @endcomponent

        @elseif($nav['type'] == 'dropdown-submenu')
        {{-- Begin submenu.. --}}
        
        {{-- DROPDOWN-SUBMENUS ARE A WISHLIST-TASK COMPONENT -> NOT IMPLEMENTED YET! --}}
        
        {{-- End submenu.. --}}

        @endif
        @endforeach

    </ul>
    <!-- END DROPDOWN MENU -->
</li>
{{-- end dropdown menu top-level link --}}

