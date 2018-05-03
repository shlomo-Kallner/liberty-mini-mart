@section('link-var-insertion')
@php
// this just so Blade does not *BARF* on our links helpers below..
if (!isset($link)) {
$link = [
'icon' => '', // the Font Awesome 4 icon class without the 'fa'.
'name' => '', // the name to fill in the Link.
'url' => '', // the URL of the link.
//'isModal' => false, // a Boolean, Is this a Modal or a URL? -@OBSOLETE!!
'type' => 'url', // 'url' for a url link, 'modal' for a modal button link.. -@NEW!
// 'type' replaces 'isModal'!
'target' => '', // the data-target attribute's data value (of a modal)
'transform' => '', // Bootstrap 3 text-transform css class.
];
}
@endphp
@endsection

{{--
    A set of helper sections for doing menu links
    where the menu links have a certain set of 'standard' forms:
    1] 'li>a' (ordinary link), 
    2] 'li>(a+(ul>li>a))' (dropdown link), 
    3] 'li>(a+(ul>li>div>div>div))' (megamenu link)
    [more to come..]
    To be embeded in a Blade @Foreach Loop, with a item
    variable named 'link', within a 'ul' tag..
--}}


@section('normal-link-helper')
<?php //dd($link); ?>
<li>
    @if( isset($link['type']) && $link['type'] == 'modal' )
    <a href="#" data-toggle="modal" data-target="{{ $link['target'] }}">
        @else
        <a href="{{ url($link['url']) }}">
            @endif
            @if($link['icon'])
            <i class="fa {{ $link['icon'] }}" aria-hidden="true"></i>
            @endif
            @if($link['transform'])
            <span class="hidden-xs {{ $link['transform'] }}">{{ $link['name'] }}</span>
            @else
            {{ $link['name'] }}
            @endif
        </a>
</li>
@endsection

@section('dropdown-link-helper')

{{-- begin dropdown menu top-level link --}}
<li class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="javascript:;" role="button">
        Pages 

    </a>

    <!-- BEGIN DROPDOWN MENU -->
    <ul class="dropdown-menu">
        @if(false)
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
        @endif
        @if(false)
        @foreach($submenus as $link)
        @yield('normal-link-helper')
        @endforeach
        @endif
        <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Running Shoes</a></li>
        <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Jackets and Coats</a></li>
    </ul>
    <!-- END DROPDOWN MENU -->
</li>
{{-- end dropdown menu top-level link --}}

@endsection

@section('menu-links-helper')
{{--
    A helper section for doing menu links
    where the menu link takes a certain 'standard' form
    of 'li>a' (ordinary link), 'li>(a+(ul>li>a))' (dropdown link) 
    or 'li>(a+(ul>li>div>div>div))' (megamenu link)..
    To be embeded in a Blade @Foreach Loop, with a item
    variable named 'link', within a 'ul' tag..
--}}
@if($link)
@endif


@endsection

