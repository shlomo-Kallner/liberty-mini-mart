
@php
$testing = true;
use \App\Utilities\Functions\Functions;

$newProducts2 = Functions::getContent($newProducts??'');

@endphp


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