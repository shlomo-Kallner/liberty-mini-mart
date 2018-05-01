
<?php
/*
 *  404 and 503 page navbar retrieval code
 */

use \App\Page;

if (!isset($navbar) || empty($navbar)) {
    $navbar = Page::getNavBar();
}
if (!isset($preheader) || empty($preheader)) {
    $preheader = [];
}

/// For testing, dump&die the $navbar variable.
//dd($navbar);
?>

@section('cut-and-paste=>my-cookie-cutter')

@endsection

@section('pre-header-navbar')
@parent

<!-- BEGIN TOP BAR -->
<div class="pre-header">
    <div class="container">
        <div class="row">
            <!-- BEGIN TOP BAR LEFT PART -->
            <div class="col-md-6 col-sm-6 additional-shop-info">
                <ul class="list-unstyled list-inline">
                    {{-- REMOVED: Phone link from template was removed. --}}
                    <!-- BEGIN CURRENCIES -->
                    <li class="shop-currencies">
                        <a href="javascript:void(0);">
                            <i class="fa fa-eur"></i>
                        </a>
                        <a href="javascript:void(0);">
                            <i class="fa fa-gbp"></i>
                        </a>
                        <a href="javascript:void(0);">
                            <i class="fa fa-ils"></i>
                        </a>
                        <a href="javascript:void(0);" class="current">
                            <i class="fa fa-usd"></i>
                        </a>
                    </li>
                    <!-- END CURRENCIES -->
                    <!-- BEGIN LANGS -->
                    <li class="langs-block">
                        <a href="javascript:void(0);" class="current">English </a>
                        <div class="langs-block-others-wrapper"><div class="langs-block-others">
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
                    <?php
                    // for testing ... $user['loggedin'] = false;
                    ?>
                    @if($preheader)
                    @foreach($preheader as $prenav)
                    <li>
                        @if( isset($prenav['isModal']) && $prenav['isModal'] === true )
                        <a href="#" data-toggle="modal" data-target="{{ $prenav['target'] }}">
                            @else
                            <a href="{{ url($prenav['url']) }}">
                                @endif
                                @if($prenav['icon'])
                                <i class="fa {{ $prenav['icon'] }}" aria-hidden="true"></i>
                                @endif
                                <span class="hidden-xs text-uppercase">{{ $prenav['name'] }}</span>
                            </a>
                    </li>
                    @endforeach
                    {{-- End single "main level" menu --}}
                    @else

                    @if($user['loggedin'])
                    <li>
                        <a href="{{ url('user') }}">
                            <i class="fa fa-id-card" aria-hidden="true"></i>
                            <span class="hidden-xs text-uppercase">My Account</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('wishlist') }}">
                            <i class="fa fa-calendar-o" aria-hidden="true"></i>
                            <span class="hidden-xs text-uppercase">My Wishlist</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('checkout') }}">
                            <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                            <span class="hidden-xs text-uppercase">Checkout</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('signout') }}">
                            <i class="fa fa-sign-out" aria-hidden="true"></i>
                            <span class="hidden-xs text-uppercase">Sign Out</span>
                        </a>
                    </li>
                    @else
                    {{-- UPDATE: changing 'Log In' url to 'Sign In' url. --}}
                    {{-- UPDATE: Copying FA icon and span tag link from 
                                 master_bootstrapious.blade.php  TOP BAR Section
                                 to replace the 'simple' text content of these 
                                 couple of links...
                     --}}
                    <li>
                        <a href="#" data-toggle="modal" data-target="#login-modal">
                            <i class="fa fa-sign-in" aria-hidden="true"></i> 
                            <span class="hidden-xs text-uppercase">Sign in</span>
                        </a>
                    </li>
                    {{-- UPDATE: adding 'Sign Up' url to TOP BAR. --}}
                    <li>
                        <a href="{{ url('signup') }}">
                            <i class="fa fa-user" aria-hidden="true"></i> 
                            <span class="hidden-xs text-uppercase">Sign up</span></a>
                        </a>
                    </li>
                    @endif

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

        <a href="javascript:void(0);" class="mobi-toggler"><i class="fa fa-bars"></i></a>

        <!-- BEGIN CART -->
        <div class="top-cart-block">
            <div class="top-cart-info">
                <a href="javascript:void(0);" class="top-cart-info-count">3 items</a>
                <a href="javascript:void(0);" class="top-cart-info-value">$1260</a>
            </div>
            <i class="fa fa-shopping-cart"></i>

            <div class="top-cart-content-wrapper">
                <div class="top-cart-content">
                    <ul class="scroller" style="height: 250px;">
                        <li>
                            <a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">
                                <img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/cart-img.jpg') }}" alt="Rolex Classic Watch" width="37" height="34">
                            </a>
                            <span class="cart-content-count">x 1</span>
                            <strong><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Rolex Classic Watch</a></strong>
                            <em>$1230</em>
                            <a href="javascript:void(0);" class="del-goods">&nbsp;</a>
                        </li>
                        <li>
                            <a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}"><img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/cart-img.jpg') }}" alt="Rolex Classic Watch" width="37" height="34"></a>
                            <span class="cart-content-count">x 1</span>
                            <strong><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Rolex Classic Watch</a></strong>
                            <em>$1230</em>
                            <a href="javascript:void(0);" class="del-goods">&nbsp;</a>
                        </li>
                        <li>
                            <a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}"><img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/cart-img.jpg') }}" alt="Rolex Classic Watch" width="37" height="34"></a>
                            <span class="cart-content-count">x 1</span>
                            <strong><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Rolex Classic Watch</a></strong>
                            <em>$1230</em>
                            <a href="javascript:void(0);" class="del-goods">&nbsp;</a>
                        </li>
                        <li>
                            <a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}"><img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/cart-img.jpg') }}" alt="Rolex Classic Watch" width="37" height="34"></a>
                            <span class="cart-content-count">x 1</span>
                            <strong><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Rolex Classic Watch</a></strong>
                            <em>$1230</em>
                            <a href="javascript:void(0);" class="del-goods">&nbsp;</a>
                        </li>
                        <li>
                            <a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}"><img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/cart-img.jpg') }}" alt="Rolex Classic Watch" width="37" height="34"></a>
                            <span class="cart-content-count">x 1</span>
                            <strong><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Rolex Classic Watch</a></strong>
                            <em>$1230</em>
                            <a href="javascript:void(0);" class="del-goods">&nbsp;</a>
                        </li>
                        <li>
                            <a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}"><img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/cart-img.jpg') }}" alt="Rolex Classic Watch" width="37" height="34"></a>
                            <span class="cart-content-count">x 1</span>
                            <strong><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Rolex Classic Watch</a></strong>
                            <em>$1230</em>
                            <a href="javascript:void(0);" class="del-goods">&nbsp;</a>
                        </li>
                        <li>
                            <a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}"><img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/cart-img.jpg') }}" alt="Rolex Classic Watch" width="37" height="34"></a>
                            <span class="cart-content-count">x 1</span>
                            <strong><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Rolex Classic Watch</a></strong>
                            <em>$1230</em>
                            <a href="javascript:void(0);" class="del-goods">&nbsp;</a>
                        </li>
                        <li>
                            <a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}"><img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/cart-img.jpg') }}" alt="Rolex Classic Watch" width="37" height="34"></a>
                            <span class="cart-content-count">x 1</span>
                            <strong><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Rolex Classic Watch</a></strong>
                            <em>$1230</em>
                            <a href="javascript:void(0);" class="del-goods">&nbsp;</a>
                        </li>
                    </ul>
                    <div class="text-right">
                        <a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-shopping-cart.html') }}" class="btn btn-default">View Cart</a>
                        <a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-checkout.html') }}" class="btn btn-primary">Checkout</a>
                    </div>
                </div>
            </div>            
        </div>
        <!--END CART -->

        <!-- BEGIN NAVIGATION -->
        <div class="header-navigation">
            <ul>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="javascript:;">
                        Woman 

                    </a>

                    <!-- BEGIN DROPDOWN MENU -->
                    <ul class="dropdown-menu">
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
                        <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Running Shoes</a></li>
                        <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">Jackets and Coats</a></li>
                    </ul>
                    <!-- END DROPDOWN MENU -->
                </li>

                <li class="dropdown dropdown-megamenu">
                    <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="javascript:;">
                        Man

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
                {{-- BEGIN single "main level" menu --}}
                {{-- Replacing Original 'Kids' menu Item with 
                     our Blade Foreach loop...  --}}
                <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Kids</a></li>
                @foreach($navbar as $nav)
                <li><a href="{{ url($nav['url']) }}">{{ $nav['name'] }}</a></li>
                @endforeach
                {{-- End single "main level" menu --}}

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

@section('header-navbar')

@endsection