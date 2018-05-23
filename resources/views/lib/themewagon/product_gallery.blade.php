
@php

    $testing = true;
    use \App\Utilities\Functions\Functions;
    
    //dd($productClass);
    
    $products2 = Functions::getBladedContent($products??'');
    //$hasContainer2 = Functions::getBladedString($hasContainer??'');
    $containerClasses2 = Functions::getBladedString($containerClasses??'');
    $sizeClass2 = Functions::getBladedString($sizeClass??'');
    $productClass2 = Functions::getBladedString($productClass??'');
    $owlClass2 = Functions::getBladedString($owlClass??'');
    $title2 = Functions::getBladedString($title??'');

@endphp

@if( Functions::testVar($products2) || $testing)

    <!-- BEGIN PRODUCT GALLERY -->
    @if( Functions::testVar($containerClasses2) )
    <div class="{{ $containerClasses2 }}">
    @endif        
        
        @if( Functions::testVar($sizeClass2) )
        <div class="{{ $sizeClass2 }} {{ $productClass2 }}">
        @endif
            @if( Functions::testVar($title2) )
            <h2>{{ $title2 }}</h2>
            @endif

            @if (Functions::testVar($owlClass2))
                <div class="owl-carousel {{ $owlClass2 }}">
            @endif
            

                @if( Functions::testVar($products2) && !$testing)

                    @foreach ($products2 as $product)
                        @component('lib.themewagon.product_mini')
                            @foreach ($product as $key => $item)
                                @slot($key)
                                    {{$item}}
                                @endslot
                            @endforeach
                        @endcomponent
                    @endforeach

                @elseif($testing)

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

                @endif

            @if (Functions::testVar($owlClass2))
                </div>
            @endif
        @if( Functions::testVar($sizeClass2) )
        </div>
        @endif
    @if( Functions::testVar($containerClasses2) )        
    </div>
    @endif
    <!-- END PRODUCT GALLERY -->

@endif
