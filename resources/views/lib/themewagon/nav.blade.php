
<?php
/*
 *  404 and 503 page navbar retrieval code
 */

use \App\Page,
    \App\Utilities\Functions\Functions,
    Darryldecode\Cart\Cart;


$navbar2 = Functions::getUnBladedContent($navbar??'');
//dd($navbar2);
if (!Functions::testVar($navbar2)) {
    $navbar2 = Page::getNavBar();
}
//dd($navbar2);


$preheader2 = Functions::getUnBladedContent($preheader??'');
//dd($preheader2);
if (!Functions::testVar($preheader2)) {
    $preheader2 = Page::getPreHeader();
}
$preheader2s = serialize($preheader2); // preserialized for component slots..

/// For testing, dump&die the $navbar variable.
//dd($navbar);
// And the $preheader variable.
//dd($preheader);


$testing = true;

if (!$testing) {
 // place some $cart initializing code here..
    $cart2 = Functions::getUnBladedContent($cart??'');
    $currency2 = Functions::getBladedString($currency??'fa-usd');
} else{
    $fakeID = 'MyFAKESEssionID123';
    //$myCart = new Cart();
    \Cart::session($fakeID);
    \Cart::session($fakeID)->add(123, 'Rolex Classic Watch', 230.5, 5, [
        'url' => 'lib/themewagon/metronicShopUI/theme/shop-item.html',
        'img' => 'lib/themewagon/metronicShopUI/theme/assets/pages/img/cart-img.jpg',
        'description' => 'Rolex Classic Watch',
    ]);
    //$cartContent = \Cart::session($fakeID)->getContent();
    $cart2 = [
        'items' => \Cart::session($fakeID)->getContent(),
        'sub-total' => \Cart::session($fakeID)->getSubTotal(),
        'total-items' => \Cart::session($fakeID)->getTotalQuantity(), // or use count() ...
    ];
    $currency2 = Functions::getBladedString($currency??'fa-usd');
}
?>



@section('user-links-panel')
    {{-- the users-links panel replaces the topbar when scrolled down.. --}}

    @component('lib.themewagon.users_panel')
        @slot('navbar')
            {!! $preheader2s !!}
        @endslot
    @endcomponent

@endsection


@section('pre-header-navbar')
    @parent

    @component('lib.themewagon.topbar')
        @slot('preheader')
            {!! $preheader2s !!}
        @endslot
    @endcomponent

@endsection




@section('header-navbar')

    <!-- BEGIN HEADER -->
    <div class="header">
        <div class="container">
            <a class="site-logo" href="{{ url('') }}">
                <img src="{{ asset('images/site/Liberty-Logo.png') }}" alt="Liberty Mini-Mart">
            </a>

            <a href="javascript:void(0);" class="mobi-toggler">
                <i class="fa fa-bars"></i>
            </a>

            <!-- BEGIN CART -->
            <div class="top-cart-block">
                <div class="top-cart-info">
                    <a href="javascript:void(0);" class="top-cart-info-count">
                        {{ $cart2['total-items'] }} 
                        {{ $cart2['total-items'] == 0 || $cart2['total-items'] > 1 ? 'items' : 'item' }}
                    </a>
                    <a href="javascript:void(0);" class="top-cart-info-value">
                        <i class="fa {{ $currency2 }}"></i>
                        {{ $cart2['sub-total'] }}
                    </a>
                </div>
                <i class="fa fa-shopping-cart"></i>

                <div class="top-cart-content-wrapper">
                    <div class="top-cart-content">
                        <ul class="scroller" style="height: 250px;">
                            @if (Functions::testVar($cart2['items']))
                                @foreach($cart2['items'] as $item)
                                    <?php //dd($item);     ?>
                                    @component('lib.themewagon.cartItem')
                                        @slot('url')
                                            {{$item->attributes['url']}}
                                        @endslot
                                        @slot('img')
                                            {{ $item->attributes['img'] }}
                                        @endslot
                                        @slot('description')
                                            {{ $item->attributes['description'] }}
                                        @endslot
                                        @slot('quantity')
                                            {{ $item->quantity }}
                                        @endslot
                                        @slot('name')
                                            {{ $item->name }}
                                        @endslot
                                        @slot('currencyIcon')
                                            {{ $currency2 }}
                                        @endslot
                                        @slot('priceSum')
                                            {{ $item->getPriceSumWithConditions() }}
                                        @endslot
                                    @endcomponent
                                @endforeach
                            @else
                                <p>Your shopping cart is empty!</p>
                            @endif
                        </ul>
                        <div class="pull-right">

                            <a href="{{ url('cart') }}" class="btn btn-default">
                                View Cart
                            </a>
                            <a href="{{ url('checkout') }}" class="btn btn-primary">
                                Checkout
                            </a>

                        </div>
                    </div>
                </div>            
            </div>
            <!--END CART -->

            <!-- BEGIN NAVIGATION -->
            <div class="header-navigation">
                <ul>

                    {{-- 
                        Replacing Original 'Kids' menu Item with 
                        our Blade Foreach loop...  

                        Moving our "main level" items to the 'front'.. 
                    --}}
                    @foreach($navbar2 as $nav)

                        @if ($nav['type'] == 'url' || $nav['type'] == 'modal' || $nav['type'] == 'dropdown')

                            {{-- BEGIN single "main level" AND dropdown menu --}}
                            @component('lib.themewagon.menu_links')
                                @foreach ($nav as $key => $value)
                                    @slot($key)
                                        @if ($key == 'submenus')
                                            {!! serialize($value) !!}
                                        @else
                                            {{ $value }}
                                        @endif
                                    @endslot
                                @endforeach
                            @endcomponent
                            {{-- End single "main level" AND dropdown menu --}}
                            
                        @elseif ($nav['type'] == 'dropdown-megamenu')
                            {{-- DROPDOWN-MEGAMENUS ARE A ADVANCED-TASK COMPONENT -> NOT IMPLEMENTED YET! --}}

                        
                        @elseif ($nav['type'] == 'nav-catalogue')
                            {{-- NAV-CATALOGUE IS AN ADVANCED-TASK COMPONENT -> NOT IMPLEMENTED YET! --}}
                            
                        @endif
                        
                    @endforeach

                    {{-- 
                        Removing the 'pages' dropdown menu that came from the template.. 

                        REMOVED: the link tag to the Premium Admin Theme at 
                        keenthemes or at 'http://themeforest.net' where 
                        we got the Metronic Shop UI Template.. 
                        there stil are links to them in the footer..
                    --}}

                    <!-- BEGIN TOP SEARCH -->
                    <li class="menu-search">
                        <span class="sep"></span>
                        <i class="fa fa-search search-modal-trigger-btn"></i> 
                    </li> 
                    <!-- END TOP SEARCH -->

                </ul>
            </div>
            <!-- END NAVIGATION -->
        </div>
    </div>
    <!-- Header END -->


@endsection

