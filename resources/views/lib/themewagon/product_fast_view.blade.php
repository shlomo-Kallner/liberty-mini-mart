
@php
    use \App\Page,
        \App\Product,
        \App\Http\Controllers\MainController,
        \App\Utilities\Functions\Functions,
        Illuminate\Support\Facades\Log;

    $requestPath = request()->path();
    //$is_admin = Functions::isAdminPath($requestPath);
    
    $prodData = [
        
        'PageNum' => 0,
        'NumShown' => 12,
        'NumItems' => 1,
        'PagingFor' => 'productsPanel',
        'Dir' => 'asc',
        'WithTrashed' => false,
        'BaseUrl' => 'store',
        'ViewNum' => 0,
        'UseBaseMaker' => false,
        'Default' => [],
        'Version' => 1,
        'UseTitle' => true,
        'FullUrl' => true,
        'ListUrl' => $requestPath,
        'UseGetSelf' => false,
        'Transform' => Product::TO_CONTENT_ARRAY_PLUS_TRANSFORM
    
    ];

    $randomProd = Product::getRandomSample(
        $prodData['NumItems'], $prodData['Transform'], 
        $prodData['BaseUrl'], 
        $prodData['UseTitle'], $prodData['FullUrl'], 
        $prodData['Version'], $prodData['WithTrashed'], 
        $prodData['Default'], $prodData['UseBaseMaker'],
        $prodData['Dir']
    );

    $currency2 = Functions::getBladedString($currency??'','fa-usd');

    $hasProduct = Functions::testVar($randomProd) && Functions::countHas($randomProd) 
        && count($randomProd) >= $prodData['NumItems'];
@endphp


{{-- TO BE FURTHER ABSTRCTIFIED... WISHLIST TASK... --}}
<!-- BEGIN fast view of a product -->
    @if(!$hasProduct)
        <div id="product-pop-up" style="display: none; width: 700px;">
            <div class="product-page product-pop-up">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-3">
                        <div class="product-main-image">
                            <img src="{{ asset('images/site/background-906143_640.jpg') }}" alt="Cool set of Chopped up Fruit" class="img-responsive">
                        </div>
                        <div class="product-other-images">
                            @if (true)
                                <a href="{{ url('images/site/background-906143_640.jpg') }}" class="active btn btn-default fancybox.image fancybox-button">
                                    <img alt="A Set of Chopped up Fruit" src="{{ asset('images/site/background-906143_640.jpg') }}">
                                </a>
                                <a href="{{ url('images/site/compare-643305_640.png') }}" class="btn btn-default fancybox.image fancybox-button">
                                    <img alt="Apples Versus Oranges" src="{{ asset('images/site/compare-643305_640.png') }}">
                                </a>
                                <a href="{{ url('images/site/experience-3239623_640.jpg') }}" class="btn btn-default fancybox.image fancybox-button">
                                    <img alt="A user filling out a questionaire" src="{{ asset('images/site/experience-3239623_640.jpg') }}">
                                </a>
                            @else
                                <a href="{{ url('images/site/background-906143_640.jpg') }}" class="active">
                                    <img alt="A Set of Chopped up Fruit" src="{{ asset('images/site/background-906143_640.jpg') }}">
                                </a>
                                <a href="{{ url('images/site/compare-643305_640.png') }}">
                                    <img alt="Apples Versus Oranges" src="{{ asset('images/site/compare-643305_640.png') }}">
                                </a>
                                <a href="{{ url('images/site/experience-3239623_640.jpg') }}">
                                    <img alt="A user filling out a questionaire" src="{{ asset('images/site/experience-3239623_640.jpg') }}">
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-9">
                        <h2>Cool green dress with red bell</h2>
                        <div class="price-availability-block clearfix">
                            <div class="price">
                                <strong><span><i class="fa {{ $currency2 }}"></i></span>47.00</strong>
                                <em><i class="fa {{ $currency2 }}"></i><span>62.00</span></em>
                            </div>
                            <div class="availability">
                                Availability: <strong>In Stock</strong>
                            </div>
                        </div>
                        <div class="description">
                            <p>Lorem ipsum dolor ut sit ame dolore  adipiscing elit, sed nonumy nibh sed euismod laoreet dolore magna aliquarm erat volutpat Nostrud duis molestie at dolore.</p>
                        </div>
                        <div class="product-page-options">
                            <div class="pull-left">
                                <label class="control-label">Size:</label>
                                <select class="form-control input-sm">
                                    <option>L</option>
                                    <option>M</option>
                                    <option>XL</option>
                                </select>
                            </div>
                            <div class="pull-left">
                                <label class="control-label">Color:</label>
                                <select class="form-control input-sm">
                                    <option>Red</option>
                                    <option>Blue</option>
                                    <option>Black</option>
                                </select>
                            </div>
                        </div>
                        <div class="product-page-cart">
                            @if (false)
                                <div class="product-quantity">
                                    <input id="product-quantity" type="text" value="1" readonly name="product-quantity" class="form-control input-sm">
                                </div>
                            @endif
                            <button class="btn btn-primary" type="submit">Add to cart</button>
                            <button class="btn btn-primary" type="submit">Order Now!</button>
                            <a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}" class="btn btn-default">More details</a>
                        </div>
                    </div>

                    <div class="sticker sticker-sale"></div>
                </div>
            </div>
        </div>
    @else

        {{-- THIS  one is to be our component.. --}}

        <div id="product-pop-up" style="display: none; width: 700px;">
            <div class="product-page product-pop-up">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-3">
                        <div class="product-main-image">
                            <img src="{{ asset($randomProd[0]['img']['img']) }}" alt="{{$randomProd[0]['title']}}" class="img-responsive">
                        </div>
                        @if (Functions::testVar($randomProd[0]['otherImages']) && Functions::countHas($randomProd[0]['otherImages']))
                            <div class="product-other-images">
                                @foreach ($randomProd[0]['otherImages'] as $otherImage)
                                    <a href="{{ url($otherImage['img']) }}" class="{{ $loop->first ? 'active' : '' }} btn btn-default fancybox.image fancybox-button">
                                        <img alt="{{ $randomProd[0]['title'] }}" src="{{ asset($otherImage['img']) }}">
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-9">
                        <h2>Cool green dress with red bell</h2>
                        <div class="price-availability-block clearfix">
                            @if (Functions::testVar($randomProd[0]['price']))
                                <div class="price">
                                    @if (Functions::testVar($randomProd[0]['sale']) && floatval($randomProd[0]['sale']) <= floatval($randomProd[0]['price']))
                                        <strong><span><i class="fa {{ $currency2 }}"></i></span>{{ round($randomProd[0]['sale'], 2, PHP_ROUND_HALF_UP) }}</strong>
                                        <em><i class="fa {{ $currency2 }}"></i><span>{{ round($randomProd[0]['price'], 2, PHP_ROUND_HALF_UP) }}</span></em>
                                    @else
                                        <strong><span><i class="fa {{ $currency2 }}"></i></span>{{ round($randomProd[0]['price'], 2, PHP_ROUND_HALF_UP) }}</strong>
                                    @endif
                                </div>
                            @endif
                            @if (Functions::isPropKeyIn($randomProd[0],'availability', false))
                                <div class="availability">
                                    Availability: <strong>{{ $randomProd[0]['availability'] }}</strong>
                                </div>
                            @endif
                        </div>
                        <div class="description">
                            <p>{{ $randomProd[0]['description'] }}</p>
                        </div>
                        @if (Functions::testVar($randomProd[0]['payload']) && Functions::countHas($randomProd[0]['payload']))
                            <div class="product-page-options">
                                <div class="pull-left">
                                    <label class="control-label">Size:</label>
                                    <select class="form-control input-sm">
                                        <option>L</option>
                                        <option>M</option>
                                        <option>XL</option>
                                    </select>
                                </div>
                                <div class="pull-left">
                                    <label class="control-label">Color:</label>
                                    <select class="form-control input-sm">
                                        <option>Red</option>
                                        <option>Blue</option>
                                        <option>Black</option>
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="product-page-cart">
                            @if (false)
                                <div class="product-quantity">
                                    <input id="product-quantity" type="text" value="1" readonly name="product-quantity" class="form-control input-sm">
                                </div>
                            @endif        
                            <a href="javascript:;" class="btn btn-primary addToCart" 
                            data-product-id="{{ $randomProd[0]['id'] }}" data-product-url="{{ url($randomProd[0]['api'] . '/addtocart') }}"
                            data-product-option="@json([])" data-redirect-to=""
                            >Add to cart</a>
                            <a href="javascript:;" class="btn btn-primary pull-right orderNow" 
                            data-product-id="{{ $randomProd[0]['id'] }}" data-product-url="{{ url($randomProd[0]['api'] . '/addtocart') }}"
                            data-product-option="@json([])" data-redirect-to="{{ url('checkout') }}"
                            >Order Now!</a>
                            <a href="{{ url($randomProd[0]['url']) }}" class="btn btn-default">More details</a>
                        </div>
                    </div>

                    @if (Functions::testVar($randomProd[0]['sticker']))
                        <div class="sticker {{ $randomProd[0]['sticker'] }}"></div>
                    @endif
                </div>
            </div>
        </div>

    @endif    
<!-- END fast view of a product -->
