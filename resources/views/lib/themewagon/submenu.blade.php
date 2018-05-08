

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
            @slot('type')
                {{ $nav['type'] }}
            @endslot
            @slot('target')
                {{ $nav['target'] }}
            @endslot
            @slot('url')
                {{$nav['url']}}
            @endslot
            @slot('name')
                {{$nav['name']}}
            @endslot
            @slot('icon')
                {{$nav['icon']}}
            @endslot
            @slot('transform')
                {{$nav['transform']}}
            @endslot
        @endcomponent

        @endif
        @endforeach
    </ul>
</li>

{{-- End submenu.. --}}