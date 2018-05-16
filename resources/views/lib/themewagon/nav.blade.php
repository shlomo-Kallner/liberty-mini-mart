
<?php
/*
 *  404 and 503 page navbar retrieval code
 */

use \App\Page;

if (!isset($navbar) || empty($navbar)) {
    $navbar = Page::getNavBar();
}
if (!isset($preheader) || empty($preheader)) {
    $preheader = Page::getPreHeader();
}

/// For testing, dump&die the $navbar variable.
//dd($navbar);
// And the $preheader variable.
//dd($preheader);

use Darryldecode\Cart\Cart;
$testing = true;
    
if ((!isset($cart) || emptyArray($cart)) && !$testing ) {
 // place some $cart initializing code here..
} elseif($testing){
    $fakeID = 'MyFAKESEssionID123';
        //$myCart = new Cart();
        \Cart::session($fakeID);
        \Cart::session($fakeID)->add(123, 'Rolex Classic Watch', 230.5, 5, [
            'url' => 'lib/themewagon/metronicShopUI/theme/shop-item.html',
            'img' => 'lib/themewagon/metronicShopUI/theme/assets/pages/img/cart-img.jpg',
            'description' => 'Rolex Classic Watch',
        ]);
        //$cartContent = \Cart::session($fakeID)->getContent();
        $cart = [
            'items' => \Cart::session($fakeID)->getContent(),
            'currency-icon' => 'fa-usd',
            'sub-total' => \Cart::session($fakeID)->getSubTotal(),
            'total-items' => \Cart::session($fakeID)->getTotalQuantity(), // or use count() ...
        ];
}
?>



@section('user-links-panel')
{{-- the users-links panel replaces the topbar when scrolled down.. --}}

    @component('lib.themewagon.users_panel')
        @slot('navbar')
            {!! serialize($preheader) !!}
        @endslot
    @endcomponent
@endsection


@section('pre-header-navbar')
@parent

<!-- BEGIN TOP BAR -->
<div class="pre-header">
    <div class="container">
        <div class="row">
            <!-- BEGIN TOP BAR LEFT PART -->
            <div class="col-md-6 col-sm-6 additional-shop-info">
                
                {{-- 
                     TO DO: Convert CURRENCIES LIST to a dynamic menu 
                     list using our links component. 
                --}}
                <ul class="list-unstyled list-inline">
                    {{-- REMOVED: the Phone link from 
                        the template was removed. --}}
                    <!-- BEGIN CURRENCIES -->
                    <li class="shop-currencies">
                        
                        {{-- Euro --}}                        
                        <a href="javascript:void(0);">
                            <i class="fa fa-eur"></i>
                        </a>
                        
                        {{-- British Pounds --}}
                        <a href="javascript:void(0);">
                            <i class="fa fa-gbp"></i>
                        </a>
                        
                        {{-- Israeli Shekels --}}
                        <a href="javascript:void(0);">
                            <i class="fa fa-ils"></i>
                        </a>

                        {{-- USA Dollars - the default --}}
                        <a href="javascript:void(0);" class="current">
                            <i class="fa fa-usd"></i>
                        </a>
                    </li>
                    <!-- END CURRENCIES -->
                    <!-- BEGIN LANGS -->
                    <li class="langs-block">
                        <a href="javascript:void(0);" class="current">English </a>
                        <div class="langs-block-others-wrapper"><div class="langs-block-others">
                                <a href="javascript:void(0);">Hebrew</a>
                                <a href="javascript:void(0);">French</a>
                                <a href="javascript:void(0);">Germany</a>
                                <a href="javascript:void(0);">Turkish</a>
                            </div></div>
                    </li>
                    <!-- END LANGS -->
                </ul>
            </div>
            <!-- END TOP BAR LEFT PART -->
            <!-- BEGIN TOP BAR MENU -->
            <!-- 
                 While the Primary/Main template for this TOP BAR MENU 
                 is Metronic Shop UI, the Internal Styling of individual
                 menu items is inspired by/copied from 
                 'bootstrapious/universal-1-0' TOP BAR MENU.
            -->
            <div class="col-md-6 col-sm-6 additional-nav">
                <ul class="list-unstyled list-inline pull-right">
                    @if($preheader)
                    {{-- UPDATE: Copying FA icon and span tag link from 
                                 master_bootstrapious.blade.php  TOP BAR Section
                                 to replace the 'simple' text content of these 
                                 couple of links...
                     --}}

                    @foreach($preheader as $nav)

                        @component('lib.themewagon.links')
                            @foreach ($nav as $key => $value)

                                @slot($key)
                                    {{ $value }}
                                @endslot
                                
                            @endforeach
                        @endcomponent
                    
                    @endforeach
                    {{-- End dynamicly generated "main level" topbar menu --}}
                    @endif

                </ul>
            </div>
            <!-- END TOP BAR MENU -->
        </div>
    </div>        
</div>
<!-- END TOP BAR -->

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
                    {{ $cart['total-items'] }} 
                    {{ $cart['total-items'] == 0 || $cart['total-items'] > 1 ? 'items' : 'item' }}
                </a>
                <a href="javascript:void(0);" class="top-cart-info-value">
                    <i class="fa {{ $cart['currency-icon'] }}"></i>
                    {{ $cart['sub-total'] }}
                </a>
            </div>
            <i class="fa fa-shopping-cart"></i>

            <div class="top-cart-content-wrapper">
                <div class="top-cart-content">
                    <ul class="scroller" style="height: 250px;">
                        @foreach($cart['items'] as $item)
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
                        {{ $cart['currency-icon'] }}
                        @endslot
                        @slot('priceSum')
                        {{ $item->getPriceSumWithConditions() }}
                        @endslot
                        @endcomponent
                        @endforeach
                    </ul>
                    <div class="text-right">

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

                {{-- Replacing Original 'Kids' menu Item with 
                     our Blade Foreach loop...  --}}
                {{-- Moving our "main level" items to the 'front'.. --}}
                @foreach($navbar as $nav)

                {{-- BEGIN single "main level" menu --}}
                @if ($nav['type'] == 'url' || $nav['type'] == 'modal')

                @component('lib.themewagon.links')
                @foreach ($nav as $key => $value)

                    @slot($key)
                        {{ $value }}
                    @endslot
                     
                @endforeach
                @endcomponent
                {{-- End single "main level" menu --}}
                
                @elseif ($nav['type'] == 'dropdown')
                    {{-- begin dropdown menu top-level link --}}
                    {{-- DROPDOWNS ARE AN ADVANCED-TASK COMPONENT -> NOT IMPLEMENTED YET! --}}
                    
                    {{-- end dropdown menu top-level link --}}
                @elseif ($nav['type'] == 'dropdown-megamenu')
                    {{-- DROPDOWN-MEGAMENUS ARE A ADVANCED-TASK COMPONENT -> NOT IMPLEMENTED YET! --}}

                
                @elseif ($nav['type'] == 'nav-catalogue')
                {{-- NAV-CATALOGUE IS AN ADVANCED-TASK COMPONENT -> NOT IMPLEMENTED YET! --}}
                    
                @endif
                

                @endforeach

                
                
                {{-- Removing the 'pages' dropdown menu that came from the template.. --}}
                


                {{-- 
                    REMOVED: the link to the Premium Admin Theme at 
                    keenthemes or at 'http://themeforest.net' where 
                    we got the Metronic Shop UI Template.. 
                --}}

                <!-- BEGIN TOP SEARCH -->
                @if(false)
                    <li class="menu-search">
                    <span class="sep"></span>
                    <i class="fa fa-search search-btn"></i>
                    <div class="search-box">
                        <form action="#">
                            <div class="input-group">
                                <input type="text" placeholder="Search" class="form-control">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="submit">Search</button>
                                </span>
                            </div>
                        </form>
                    </div>
                    @else
                    <li role="separator" class="divider">
                        <span class="sep"></span>
                    </li>
                    <span class="sep"></span>
                    <li>
                    <a href="#" data-toggle="modal" data-target="#search-modal">
                            <i class="fa fa-search"></i>
                    </a>
                    @endif 
                </li>
                <!-- END TOP SEARCH -->
            </ul>
        </div>
        <!-- END NAVIGATION -->
    </div>
</div>
<!-- Header END -->


@endsection

