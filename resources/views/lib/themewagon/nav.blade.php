
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
// to be removed.. -> submenus, dropdow
$temp = []; 
$temp[] = Page::genURLMenuItem('about', 'ABOUT' ); 
$temp[] = Page::genURLMenuItem('store', 'STORE' ); 
$submenus = serialize( $temp );

?>

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
                    @slot('url')
                    {{$nav['url']}}
                    @endslot
                    @slot('name')
                    {{$nav['name']}}
                    @endslot
                    @slot('target')
                    {{$nav['target']}}
                    @endslot
                    @slot('type')
                    {{$nav['type']}}
                    @endslot
                    @slot('icon')
                    {{$nav['icon']}}
                    @endslot
                    @slot('transform')
                    {{$nav['transform']}}
                    @endslot
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
                {{-- End single "main level" menu --}}
                
                    
                {{-- begin dropdown menu top-level link --}}
                @elseif ($nav['type'] == 'dropdown')
                    {{-- DROPDOWNS ARE AN ADVANCED-TASK COMPONENT -> NOT IMPLEMENTED YET! --}}

                    @if(false)
                        @component('lib.themewagon.dropdowns')
                        @slot('submenus')
                        {{ 
                            isset($menu['submenus']) 
                            ? ( is_array($menu['submenus']) ? serialize($menu['submenus']) 
                                : ( is_string($menu['submenus']) ? $menu['submenus'] : '' )
                            )
                            : '' 
                        }}
                        @endslot
                        @endcomponent
                    @endif
                
                @elseif ($nav['type'] == 'dropdown-megamenu')
                    {{-- DROPDOWN-MEGAMENUS ARE A ADVANCED-TASK COMPONENT -> NOT IMPLEMENTED YET! --}}
                    
                    @if(false)
                        @component('lib.themewagon.megamenus') 
                        {{-- the file of this component does not exist! --}}
                        
                        @slot('submenus')
                        {{ 
                            isset($menu['submenus']) 
                            ? ( is_array($menu['submenus']) ? serialize($menu['submenus']) 
                                : ( is_string($menu['submenus']) ? $menu['submenus'] : '' )
                            )
                            : '' 
                        }}
                        @endslot
                        @endcomponent
                    @endif
                
                @endif
                

                @endforeach

                
                @if (false)
                    
                @else

                <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="javascript:;" role="button">
                            Pages 
    
                        </a>
    
                        <!-- BEGIN DROPDOWN MENU -->
                        <ul class="dropdown-menu">
                            
                            {{-- REMOVED: Static dropdown-submenu from our template. --}}
                            
                            @if(isset($submenus))
                            <?php //dd(unserialize($submenus)); ?>
                            @foreach(unserialize($submenus) as $menu)
                            <?php //var_dump($menu['icon']); ?>
                            @component('lib.themewagon.links')
                            @slot('type')
                            {{ $menu['type'] }}
                            @endslot
                            @slot('url')
                            {{ $menu['url'] }}
                            @endslot
                            @slot('name')
                            {{ $menu['name'] }}
                            @endslot
                            @slot('icon')
                            {{ $menu['icon'] }}
                            @endslot
                            @slot('transform')
                            {{ $menu['transform'] }}
                            @endslot
                            
                            @endcomponent
                            @endforeach
                            @else
                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Running Shoes</a></li>
                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Jackets and Coats</a></li>
                            @endif
                        </ul>
                        <!-- END DROPDOWN MENU -->
                    </li>
                    {{-- end dropdown menu top-level link --}}
    
                    
                @endif
                
                
                {{-- begin megamenu top-level link --}}
                <li class="dropdown dropdown-megamenu">
                    <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="javascript:;">
                        Shop

                    </a>
                    {{-- BEGIN MEGAMENU --}}
                    <ul class="dropdown-menu">
                        <li>
                            <div class="header-navigation-content">
                                <div class="row">
                                    <div class="col-md-4 header-navigation-col">
                                        <h4>Footwear</h4>
                                        <ul>
                                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Astro Trainers</a></li>
                                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Basketball Shoes</a></li>
                                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Boots</a></li>
                                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Canvas Shoes</a></li>
                                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Football Boots</a></li>
                                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Golf Shoes</a></li>
                                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Hi Tops</a></li>
                                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Indoor and Court Trainers</a></li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4 header-navigation-col">
                                        <h4>Clothing</h4>
                                        <ul>
                                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Base Layer</a></li>
                                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Character</a></li>
                                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Chinos</a></li>
                                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Combats</a></li>
                                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Cricket Clothing</a></li>
                                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Fleeces</a></li>
                                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Gilets</a></li>
                                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Golf Tops</a></li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4 header-navigation-col">
                                        <h4>Accessories</h4>
                                        <ul>
                                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Belts</a></li>
                                            <li><a href="urllib/themewagon/metronicShopUI/theme/shop-product-list.html">Caps</a></li>
                                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Gloves, Hats and Scarves</a></li>
                                        </ul>

                                        <h4>Clearance</h4>
                                        <ul>
                                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Jackets</a></li>
                                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Bottoms</a></li>
                                        </ul>
                                    </div>
                                    <div class="col-md-12 nav-brands">
                                        <ul>
                                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}"><img title="esprit" alt="esprit" src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/brands/esprit.jpg') }}"></a></li>
                                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}"><img title="gap" alt="gap" src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/brands/gap.jpg') }}"></a></li>
                                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}"><img title="next" alt="next" src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/brands/next.jpg') }}"></a></li>
                                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}"><img title="puma" alt="puma" src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/brands/puma.jpg') }}"></a></li>
                                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}"><img title="zara" alt="zara" src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/brands/zara.jpg') }}"></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    {{-- END MEGAMENU --}}
                </li>
                {{-- end megamenu top-level link --}}

                {{-- NAV-CATALOGUE - will be implemented 
                     in the Advanced level! --}}
                @if(false)
                <li class="dropdown dropdown100 nav-catalogue">
                    <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="javascript:;">
                        New

                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <div class="header-navigation-content">
                                <div class="row">
                                    <div class="col-md-3 col-sm-4 col-xs-6">
                                        <div class="product-item">
                                            <div class="pi-img-wrapper">
                                                <a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}"><img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model4.jpg') }}" class="img-responsive" alt="Berry Lace Dress"></a>
                                            </div>
                                            <h3><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Berry Lace Dress</a></h3>
                                            <div class="pi-price">$29.00</div>
                                            <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-4 col-xs-6">
                                        <div class="product-item">
                                            <div class="pi-img-wrapper">
                                                <a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}"><img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model3.jpg') }}" class="img-responsive" alt="Berry Lace Dress"></a>
                                            </div>
                                            <h3><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Berry Lace Dress</a></h3>
                                            <div class="pi-price">$29.00</div>
                                            <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-4 col-xs-6">
                                        <div class="product-item">
                                            <div class="pi-img-wrapper">
                                                <a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}"><img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model7.jpg') }}" class="img-responsive" alt="Berry Lace Dress"></a>
                                            </div>
                                            <h3><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Berry Lace Dress</a></h3>
                                            <div class="pi-price">$29.00</div>
                                            <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-4 col-xs-6">
                                        <div class="product-item">
                                            <div class="pi-img-wrapper">
                                                <a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}"><img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model4.jpg') }}" class="img-responsive" alt="Berry Lace Dress"></a>
                                            </div>
                                            <h3><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Berry Lace Dress</a></h3>
                                            <div class="pi-price">$29.00</div>
                                            <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
                @endif
                {{-- Removing template/`s 'pages' dropdown menu.. --}}
                @if(false)
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="javascript:;">
                        Pages 

                    </a>

                    <ul class="dropdown-menu">
                        <li class="active"><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-index.html') }}">Home Default</a></li>
                        <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-index-header-fix.html') }}">Home Header Fixed</a></li>
                        <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-index-light-footer.html') }}">Home Light Footer</a></li>
                        <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Product List</a></li>
                        <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-search-result.html') }}">Search Result</a></li>
                        <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Product Page</a></li>
                        <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-shopping-cart-null.html') }}">Shopping Cart (Null Cart)</a></li>
                        <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-shopping-cart.html') }}">Shopping Cart</a></li>
                        <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-checkout.html') }}">Checkout</a></li>
                        <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-about.html') }}">About</a></li>
                        <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-contacts.html') }}">Contacts</a></li>
                        <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-account.html') }}">My account</a></li>
                        <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-wishlist.html') }}">My Wish List</a></li>
                        <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-goods-compare.html') }}">Product Comparison</a></li>
                        <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-standart-forms.html') }}">Standart Forms</a></li>
                        <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-faq.html') }}">FAQ</a></li>
                        <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-privacy-policy.html') }}">Privacy Policy</a></li>
                        <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-terms-conditions-page.html') }}">Terms &amp; Conditions</a></li>
                    </ul>
                </li>
                @endif


                {{-- 
                    REMOVED: the link to the Premium Admin Theme at 
                    keenthemes or at 'http://themeforest.net' where 
                    we got the Metronic Shop UI Template.. 
                --}}

                <!-- BEGIN TOP SEARCH -->
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
                </li>
                <!-- END TOP SEARCH -->
            </ul>
        </div>
        <!-- END NAVIGATION -->
    </div>
</div>
<!-- Header END -->


@endsection

