

{{-- 
     IS THIS A COMPONENT OR A VIEW? 
     FOR NOW TREATING IT AS A COMPONENT... 

     UPDATE: IT _IS_ A COMPONENT!!!
--}}

{{-- OUR SLOTS... --}}
@php
    $testing = true;
    use \App\Utilities\Functions\Functions;

    $currency2 = 'fa-usd';

    $image2 = 'assets/pages/img/products/model7.jpg';
    $imageAlt2 = 'Cool green dress with red bell';
    $otherImages2 = [
        [
            'image' => 'assets/pages/img/products/model3.jpg',
            'alt' => 'Berry Lace Dress',
        ],
        [
            'image' => 'assets/pages/img/products/model4.jpg',
            'alt' => 'Berry Lace Dress',
        ],
        [
            'image' => 'assets/pages/img/products/model5.jpg',
            'alt' => 'Berry Lace Dress',
        ]
    ];
    $productTitle2 = 'Cool green dress with red bell';
    $productPrice2 = '62.00';
    $productSalePrice2 = '47.00';
    $productAvailability2 = 'In Stock';
    $productShortDescription2 = '<p>
                                    Lorem ipsum dolor ut sit ame dolore  adipiscing elit, 
                                    sed nonumy nibh sed euismod laoreet dolore magna aliquarm 
                                    erat volutpat Nostrud duis molestie at dolore.
                                 </p>';
    $productLongDescription2 = '<p>
                                    Lorem ipsum dolor ut sit ame dolore  
                                    adipiscing elit, sed sit nonumy nibh 
                                    sed euismod laoreet dolore magna aliquarm 
                                    erat sit volutpat Nostrud duis molestie at 
                                    dolore. Lorem ipsum dolor ut sit ame dolore  
                                    adipiscing elit, sed sit nonumy nibh sed 
                                    euismod laoreet dolore magna aliquarm erat 
                                    sit volutpat Nostrud duis molestie at dolore. 
                                    Lorem ipsum dolor ut sit ame dolore  
                                    adipiscing elit, sed sit nonumy nibh 
                                    sed euismod laoreet dolore magna aliquarm 
                                    erat sit volutpat Nostrud duis molestie at 
                                    dolore. 
                                </p>';
    $productRating2 = '4';
    // reviews are a Wishlist item!
    $productReviews2 = [
        [
            'author' => 'Bob',
            'date' => '30/12/2013 - 07:37',
            'rating' => '5',
            'content' =>    '<p>
                                Sed velit quam, auctor id semper a, 
                                hendrerit eget justo. Cum sociis natoque 
                                penatibus et magnis dis parturient montes, 
                                nascetur ridiculus mus. Duis vel arcu 
                                pulvinar dolor tempus feugiat id in orci. 
                                Phasellus sed erat leo. Donec luctus, 
                                justo eget ultricies tristique, enim 
                                mauris bibendum orci, a sodales lectus 
                                purus ut lorem.
                            </p>'
        ],
        [
            'author' => 'Mary',
            'date' => '13/12/2013 - 17:49',
            'rating' => '2.5',
            'content' => '<p>
                            Sed velit quam, auctor id semper a, 
                            hendrerit eget justo. Cum sociis 
                            natoque penatibus et magnis dis 
                            parturient montes, nascetur 
                            ridiculus mus. Duis vel arcu 
                            pulvinar dolor tempus feugiat id 
                            in orci. Phasellus sed erat leo. 
                            Donec luctus, justo eget ultricies 
                            tristique, enim mauris bibendum orci, 
                            a sodales lectus purus ut lorem.
                        </p>'
        ]
    ];
    $numProductReviews = count($productReviews2);
    $productAdditionalInfo2 = [
        [
            'name' => 'Value 1',
            'item' => '21 cm'
        ],
        [
            'name' => 'Value 2',
            'item' => '700 gr.'
        ],
        [
            'name' => 'Value 3',
            'item' => '10 person'
        ],
        [
            'name' => 'Value 4',
            'item' => '14 cm'
        ],
        [
            'name' => 'Value 5',
            'item' => 'plastic'
        ],
        [
            'name' => '',
            'item' => ''
        ]
    ];
    $productSticker2 = 'sticker-sale';
    
@endphp


    <!-- BEGIN CONTENT -->
        <div class="col-md-9 col-sm-7">
            <div class="product-page">
              <div class="row">
                <div class="col-md-6 col-sm-6">
                  <div class="product-main-image">
                    <img src="{{ asset($image2) }}" alt="{{ $imageAlt2 }}" class="img-responsive" data-BigImgsrc="{{ asset($image2) }}">
                  </div>
                  <div class="product-other-images">
                    @foreach ($otherImages2 as $item)
                        <a href="{{ asset($item['image']) }}" class="fancybox-button" rel="photos-lib">
                            <img alt="{{ $item['alt'] }}" src="{{ asset($item['image']) }}">
                        </a>
                    @endforeach
                  </div>
                </div>
                <div class="col-md-6 col-sm-6">
                  <h1>{{ $productTitle2 }}</h1>
                  <div class="price-availability-block clearfix">
                    <div class="price">
                        @if (Functions::testVar($productSalePrice2))
                            <strong>
                                <span><i class="fa {{ $currency2 }}"></i></span>
                                {{ $productSalePrice2 }}
                            </strong>
                            <em>
                                <i class="fa {{ $currency2 }}"></i>
                                <span>{{ $productPrice2 }}</span>
                            </em>
                        @else
                            <strong>
                                <span><i class="fa {{ $currency2 }}"></i></span>
                                {{ $productPrice2 }}
                            </strong>
                        @endif
                    </div>
                    @if (Functions::testVar($productAvailability2))
                        <div class="availability">
                            Availability: <strong>{{ $productAvailability2 }}</strong>
                        </div>
                    @endif
                  </div>
                  <div class="description">
                    {!! $productShortDescription2 !!}
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
                    <div class="product-quantity">
                        <input id="product-quantity" type="text" value="1" readonly class="form-control input-sm">
                    </div>
                    <button class="btn btn-primary addToCart" type="submit" id="mainProductAdder">Add to cart</button>
                    <button class="btn btn-primary orderNow" type="submit" id="mainProductOrder">Order Now!</button>
                  </div>
                  <div class="review">
                    <input type="range" value="{{ $productRating2 }}" step="0.25" id="backing4">
                    <div class="rateit" data-rateit-backingfld="#backing4" data-rateit-resetable="false"  data-rateit-ispreset="true" data-rateit-min="0" data-rateit-max="5">
                    </div>
                    <a href="#Reviews" data-toggle="tab">
                        @if ($numProductReviews > 1 || $numProductReviews === 0 )
                            {{ $numProductReviews }} reviews
                        @else
                            {{ $numProductReviews }} review
                        @endif
                    </a>
                    &nbsp;&nbsp;|&nbsp;&nbsp;
                    <a href="javascript:;">Write a review</a>
                  </div>
                  <ul class="social-icons">
                    <li><a class="facebook" data-original-title="facebook" href="javascript:;"></a></li>
                    <li><a class="twitter" data-original-title="twitter" href="javascript:;"></a></li>
                    <li><a class="googleplus" data-original-title="googleplus" href="javascript:;"></a></li>
                    <li><a class="evernote" data-original-title="evernote" href="javascript:;"></a></li>
                    <li><a class="tumblr" data-original-title="tumblr" href="javascript:;"></a></li>
                  </ul>
                </div>

                <div class="product-page-content">
                  <ul id="myTab" class="nav nav-tabs">
                    <li><a href="#Description" data-toggle="tab">Description</a></li>
                    <li><a href="#Information" data-toggle="tab">Information</a></li>
                    <li class="active"><a href="#Reviews" data-toggle="tab">Reviews ({{ $numProductReviews }})</a></li>
                  </ul>
                  <div id="myTabContent" class="tab-content">
                    <div class="tab-pane fade" id="Description">
                        {!! $productLongDescription2 !!}
                    </div>
                    <div class="tab-pane fade" id="Information">
                      <table class="datasheet">
                        <tr>
                          <th colspan="2">Additional features</th>
                        </tr>
                        @foreach ($productAdditionalInfo2 as $info)
                            <tr>
                                <td class="datasheet-features-type">{!! $info['name'] !!}</td>
                                <td>{!! $info['item'] !!}</td>
                              </tr>
                        @endforeach
                      </table>
                    </div>
                    <div class="tab-pane fade in active" id="Reviews">
                        @if (Functions::testVar($productReviews2))
                            @foreach ($productReviews2 as $review)
                                <div class="review-item clearfix">
                                    <div class="review-item-submitted">
                                        <strong>{!! $review['author'] !!}</strong>
                                        <em>{!! $review['date'] !!}</em>
                                        <div class="rateit" data-rateit-value="{!! $review['rating'] !!}" data-rateit-ispreset="true" data-rateit-readonly="true"></div>
                                    </div>                                              
                                    <div class="review-item-content">
                                        {!! $review['content'] !!}
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p>There are no reviews for this product.</p>
                        @endif
                        
                        <!-- BEGIN FORM-->
                        <form action="#" class="reviews-form" role="form">
                            <h2>Write a review</h2>
                            <div class="form-group">
                            <label for="name">Name <span class="require">*</span></label>
                            <input type="text" class="form-control" id="name">
                            </div>
                            <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" id="email">
                            </div>
                            <div class="form-group">
                            <label for="review-summernote">Review <span class="require">*</span></label>
                            <textarea class="form-control" rows="8" id="review-summernote"></textarea>
                            </div>
                            <div class="form-group">
                            <label for="email">Rating</label>
                            <input type="range" value="4" step="0.25" id="backing5">
                            <div class="rateit" data-rateit-backingfld="#backing5" data-rateit-resetable="true"  data-rateit-ispreset="true" data-rateit-min="0" data-rateit-max="5">
                            </div>
                            </div>
                            <div class="padding-top-20">                  
                            <button type="submit" class="btn btn-primary">Send</button>
                            </div>
                        </form>
                        <script>
                                $(document).ready(function($) 
                                {
                                    $('#review-summernote').summernote();
                                });
                        </script>
                        <!-- END FORM--> 
                    </div>
                  </div>
                </div>
                
                <div class="sticker {!! $productSticker2 !!}"></div>
              </div>
            </div>
        </div>
    <!-- END CONTENT -->
