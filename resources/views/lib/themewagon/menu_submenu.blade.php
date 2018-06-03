

{{-- NOTE: this is a WISHLIST-TASK COMPONENT! 
    Not to be done until the main project is completed! --}}


{{-- Begin submenu.. --}}
        
<li class="dropdown-submenu">
    <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="javascript:;" role="button">
        {{$name}} <i class="fa fa-angle-right"></i>
    </a>
    <ul class="dropdown-menu" role="menu">
        @foreach(unserialize($submenus) as $nav)
        @if( $nav['type'] == 'url' || $nav['type'] == 'modal' )

        @component('lib.themewagon.links')
            @foreach ($nav as $key => $value)

                @slot($key)
                    {{ $value }}
                @endslot
                    
            @endforeach
        @endcomponent

        @endif
        @endforeach
    </ul>
</li>

{{-- End submenu.. --}}