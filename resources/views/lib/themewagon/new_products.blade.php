
@php

    $testing = true;
    use \App\Utilities\Functions\Functions;
    
    $newProducts2 = Functions::getContent($newProducts??'');
    $sampleProducts2 = Functions::getContent($sampleProducts??'');
    $currency2 = Functions::getContent($currency??'');
    $sidebar2 = Functions::getContent($sidebar??'');
    $filters2 = Functions::getContent($filters2??'');
    $bestsellers2 = Functions::getContent($bestsellers??'');

@endphp

@if(true)
    <!-- BEGIN SALE PRODUCT & NEW ARRIVALS -->
    @if (true)

        @component('lib.themewagon.product_gallery')
            @slot('products')
                @if('continue_escaping_$products' !== '')
                {{ $newProducts2 }}
                @else
                {!! $newProducts2 !!}
                @endif
            @endslot
            @slot('containerClasses')
                {{ "row margin-bottom-40 margin-top-30" }}
            @endslot
            @slot('sizeClass')
                {{ "col-md-12" }}
            @endslot
            @slot('productClass')
                {{ "sale-product" }}
            @endslot
            @slot('owlClass')
                {{ "owl-carousel5" }}
            @endslot
            @slot('title')
                {{ "New Arrivals" }}
            @endslot
        @endcomponent

    @else

        <div class="row margin-bottom-40 margin-top-30">
            <!-- BEGIN SALE PRODUCT -->
            <div class="col-md-12 sale-product">
                <h2>New Arrivals</h2>
                <div class="owl-carousel owl-carousel5">
                    <div>
                        <div class="product-item">
                            <div class="pi-img-wrapper">
                                <img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model1.jpg') }}" class="img-responsive" alt="Berry Lace Dress">
                                <div>
                                    <a href="{{ url('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model1.jpg') }}" class="btn btn-default fancybox-button">Zoom</a>
                                    <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                                </div>
                            </div>
                            <h3><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Berry Lace Dress</a></h3>
                            <div class="pi-price">$29.00</div>
                            <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                            <div class="sticker sticker-sale"></div>
                        </div>
                    </div>
                    <div>
                        <div class="product-item">
                            <div class="pi-img-wrapper">
                                <img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model2.jpg') }}" class="img-responsive" alt="Berry Lace Dress">
                                <div>
                                    <a href="{{ url('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model2.jpg') }}" class="btn btn-default fancybox-button">Zoom</a>
                                    <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                                </div>
                            </div>
                            <h3><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Berry Lace Dress2</a></h3>
                            <div class="pi-price">$29.00</div>
                            <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                        </div>
                    </div>
                    <div>
                        <div class="product-item">
                            <div class="pi-img-wrapper">
                                <img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model6.jpg') }}" class="img-responsive" alt="Berry Lace Dress">
                                <div>
                                    <a href="{{ url('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model6.jpg') }}" class="btn btn-default fancybox-button">Zoom</a>
                                    <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                                </div>
                            </div>
                            <h3><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Berry Lace Dress2</a></h3>
                            <div class="pi-price">$29.00</div>
                            <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                        </div>
                    </div>
                    <div>
                        <div class="product-item">
                            <div class="pi-img-wrapper">
                                <img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model4.jpg') }}" class="img-responsive" alt="Berry Lace Dress">
                                <div>
                                    <a href="{{ url('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model4.jpg') }}" class="btn btn-default fancybox-button">Zoom</a>
                                    <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                                </div>
                            </div>
                            <h3><a href="javascript:;">Berry Lace Dress4</a></h3>
                            <div class="pi-price">$29.00</div>
                            <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                            <div class="sticker sticker-new"></div>
                        </div>
                    </div>
                    <div>
                        <div class="product-item">
                            <div class="pi-img-wrapper">
                                <img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model5.jpg') }}" class="img-responsive" alt="Berry Lace Dress">
                                <div>
                                    <a href="{{ url('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model5.jpg') }}" class="btn btn-default fancybox-button">Zoom</a>
                                    <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                                </div>
                            </div>
                            <h3><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Berry Lace Dress5</a></h3>
                            <div class="pi-price">$29.00</div>
                            <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                        </div>
                    </div>
                    <div>
                        <div class="product-item">
                            <div class="pi-img-wrapper">
                                <img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model3.jpg') }}" class="img-responsive" alt="Berry Lace Dress">
                                <div>
                                    <a href="{{ url('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model3.jpg') }}" class="btn btn-default fancybox-button">Zoom</a>
                                    <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                                </div>
                            </div>
                            <h3><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Berry Lace Dress3</a></h3>
                            <div class="pi-price">$29.00</div>
                            <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                        </div>
                    </div>
                    <div>
                        <div class="product-item">
                            <div class="pi-img-wrapper">
                                <img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model7.jpg') }}" class="img-responsive" alt="Berry Lace Dress">
                                <div>
                                    <a href="{{ url('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model7.jpg') }}" class="btn btn-default fancybox-button">Zoom</a>
                                    <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                                </div>
                            </div>
                            <h3><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Berry Lace Dress3</a></h3>
                            <div class="pi-price">$29.00</div>
                            <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END SALE PRODUCT -->
        </div>
        
    @endif
    <!-- END SALE PRODUCT & NEW ARRIVALS -->
@endif

@if(true)
    <!-- BEGIN SIDEBAR & CONTENT -->
    <div class="row margin-bottom-40 ">
    
        @if(false)
            <!-- BEGIN SIDEBAR -->
            <div class="sidebar col-md-3 col-sm-4">
                <ul class="list-group margin-bottom-25 sidebar-menu">
                    <li class="list-group-item clearfix"><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}"><i class="fa fa-angle-right"></i> Ladies</a></li>
                    <li class="list-group-item clearfix dropdown">
                        <a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}">
                            <i class="fa fa-angle-right"></i>
                            Mens

                        </a>
                        <ul class="dropdown-menu">
                            <li class="list-group-item dropdown clearfix">
                                <a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}"><i class="fa fa-angle-right"></i> Shoes </a>
                                <ul class="dropdown-menu">
                                    <li class="list-group-item dropdown clearfix">
                                        <a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}"><i class="fa fa-angle-right"></i> Classic </a>
                                        <ul class="dropdown-menu">
                                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}"><i class="fa fa-angle-right"></i> Classic 1</a></li>
                                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}"><i class="fa fa-angle-right"></i> Classic 2</a></li>
                                        </ul>
                                    </li>
                                    <li class="list-group-item dropdown clearfix">
                                        <a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}"><i class="fa fa-angle-right"></i> Sport  </a>
                                        <ul class="dropdown-menu">
                                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}"><i class="fa fa-angle-right"></i> Sport 1</a></li>
                                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}"><i class="fa fa-angle-right"></i> Sport 2</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}"><i class="fa fa-angle-right"></i> Trainers</a></li>
                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}"><i class="fa fa-angle-right"></i> Jeans</a></li>
                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}"><i class="fa fa-angle-right"></i> Chinos</a></li>
                            <li><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}"><i class="fa fa-angle-right"></i> T-Shirts</a></li>
                        </ul>
                    </li>
                    <li class="list-group-item clearfix"><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}"><i class="fa fa-angle-right"></i> Kids</a></li>
                    <li class="list-group-item clearfix"><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}"><i class="fa fa-angle-right"></i> Accessories</a></li>
                    <li class="list-group-item clearfix"><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}"><i class="fa fa-angle-right"></i> Sports</a></li>
                    <li class="list-group-item clearfix"><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}"><i class="fa fa-angle-right"></i> Brands</a></li>
                    <li class="list-group-item clearfix"><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}"><i class="fa fa-angle-right"></i> Electronics</a></li>
                    <li class="list-group-item clearfix"><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}"><i class="fa fa-angle-right"></i> Home & Garden</a></li>
                    <li class="list-group-item clearfix"><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-product-list.html') }}"><i class="fa fa-angle-right"></i> Custom Link</a></li>
                </ul>
            </div>
            <!-- END SIDEBAR -->
        @else
            @component('lib.themewagon.sidebar')
                @slot('sidebar')
                    {!! $sidebar2 !!}
                @endslot
                @slot('filters')
                    {!! $filters2 !!}
                @endslot
                @slot('bestsellers')
                    {!! $bestsellers2 !!}
                @endslot
                @slot('currency')
                    {{ $currency2 }}
                @endslot
            @endcomponent
        @endif
            
        @if(true)
            <!-- BEGIN CONTENT -->
            @if(true)

                @component('lib.themewagon.product_gallery')
                    @slot('products')
                        @if('continue_escaping_$products' !== '')
                        {{ $sampleProducts2 }}
                        @else
                        {!! $sampleProducts2 !!}
                        @endif
                    @endslot
                    @slot('sizeClass')
                        {{ "col-md-9 col-sm-8" }}
                    @endslot
                    @slot('owlClass')
                        {{ "owl-carousel3" }}
                    @endslot
                    @slot('title')
                        {{ "Three items" }}
                    @endslot
                @endcomponent
            
            @else

                <div class="col-md-9 col-sm-8">
                    <h2>Three items</h2>
                    <div class="owl-carousel owl-carousel3">
                        <div>
                            <div class="product-item">
                                <div class="pi-img-wrapper">
                                    <img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/k1.jpg') }}" class="img-responsive" alt="Berry Lace Dress">
                                    <div>
                                        <a href="{{ url('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/k1.jpg') }}" class="btn btn-default fancybox-button">Zoom</a>
                                        <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                                    </div>
                                </div>
                                <h3><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Berry Lace Dress</a></h3>
                                <div class="pi-price">$29.00</div>
                                <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                                <div class="sticker sticker-new"></div>
                            </div>
                        </div>
                        <div>
                            <div class="product-item">
                                <div class="pi-img-wrapper">
                                    <img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/k2.jpg') }}" class="img-responsive" alt="Berry Lace Dress">
                                    <div>
                                        <a href="{{ url('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/k2.jpg') }}" class="btn btn-default fancybox-button">Zoom</a>
                                        <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                                    </div>
                                </div>
                                <h3><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Berry Lace Dress2</a></h3>
                                <div class="pi-price">$29.00</div>
                                <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                            </div>
                        </div>
                        <div>
                            <div class="product-item">
                                <div class="pi-img-wrapper">
                                    <img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/k3.jpg') }}" class="img-responsive" alt="Berry Lace Dress">
                                    <div>
                                        <a href="{{ url('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/k3.jpg') }}" class="btn btn-default fancybox-button">Zoom</a>
                                        <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                                    </div>
                                </div>
                                <h3><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Berry Lace Dress3</a></h3>
                                <div class="pi-price">$29.00</div>
                                <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                            </div>
                        </div>
                        <div>
                            <div class="product-item">
                                <div class="pi-img-wrapper">
                                    <img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/k4.jpg') }}" class="img-responsive" alt="Berry Lace Dress">
                                    <div>
                                        <a href="{{ url('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/k4.jpg') }}" class="btn btn-default fancybox-button">Zoom</a>
                                        <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                                    </div>
                                </div>
                                <h3><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Berry Lace Dress4</a></h3>
                                <div class="pi-price">$29.00</div>
                                <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                                <div class="sticker sticker-sale"></div>
                            </div>
                        </div>
                        <div>
                            <div class="product-item">
                                <div class="pi-img-wrapper">
                                    <img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/k1.jpg') }}" class="img-responsive" alt="Berry Lace Dress">
                                    <div>
                                        <a href="{{ url('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/k1.jpg') }}" class="btn btn-default fancybox-button">Zoom</a>
                                        <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                                    </div>
                                </div>
                                <h3><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Berry Lace Dress5</a></h3>
                                <div class="pi-price">$29.00</div>
                                <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                            </div>
                        </div>
                        <div>
                            <div class="product-item">
                                <div class="pi-img-wrapper">
                                    <img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/k2.jpg') }}" class="img-responsive" alt="Berry Lace Dress">
                                    <div>
                                        <a href="{{ url('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/k2.jpg') }}" class="btn btn-default fancybox-button">Zoom</a>
                                        <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                                    </div>
                                </div>
                                <h3><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Berry Lace Dress6</a></h3>
                                <div class="pi-price">$29.00</div>
                                <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                            </div>
                        </div>
                    </div>
                </div>
                
            @endif
            <!-- END CONTENT -->
        @endif
    </div>
    <!-- END SIDEBAR & CONTENT -->
@endif



