

{{-- NOTE: this is a ADVANCED-TASK COMPONENT! 
    Not to be done until the main project is completed! --}}


{{-- begin dropdown menu top-level link --}}
<li class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="javascript:;" role="button">
        {{ $name }}

    </a>

    <!-- BEGIN DROPDOWN MENU -->
    <ul class="dropdown-menu">
        @if($submenus)
        @foreach(unserialize($submenus) as $nav)
        @if( $nav['type'] == 'url' || $nav['type'] == 'modal' )
        @component('lib.themewagon.links')
        @slot('type')
        {{$nav['type']}}
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
        
        @if (false)
        {{-- DROPDOWN-SUBMENUS ARE A WISHLIST-TASK COMPONENT -> NOT IMPLEMENTED YET! --}}
        @slot('submenus')
        {{ $nav['submenus'] }}
        @endslot
        
        @endif
        
        @endcomponent
        @elseif($nav['type'] == 'dropdown' || $nav['type'] == 'dropdown-submenu')
        {{-- Begin submenu.. --}}
        
        {{-- DROPDOWN-SUBMENUS ARE A WISHLIST-TASK COMPONENT -> NOT IMPLEMENTED YET! --}}
        
        {{-- End submenu.. --}}

        
        @endif
        @endforeach
        @else
        <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Running Shoes</a></li>
        <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Jackets and Coats</a></li>
        @endif
    </ul>
    <!-- END DROPDOWN MENU -->
</li>
{{-- end dropdown menu top-level link --}}

