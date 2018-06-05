

{{-- 
     IS THIS A COMPONENT OR A VIEW? 
     FOR NOW TREATING IT AS A COMPONENT... 

     UPDATE: IT _IS_ A COMPONENT!!!
--}}

{{-- OUR SLOTS... --}}
@php
    $testing = false;
    use \App\Utilities\Functions\Functions;

    
    if ($testing) {
        $currency2 = 'fa-usd';
        $image2 = 'lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model7.jpg';
        $imageAlt2 = 'Cool green dress with red bell';
        $otherImages2 = [
            [
                'image' => 'lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model3.jpg',
                'alt' => 'Berry Lace Dress',
            ],
            [
                'image' => 'lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model4.jpg',
                'alt' => 'Berry Lace Dress',
            ],
            [
                'image' => 'lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model5.jpg',
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
        $productOptions2 = [
            'size' => [
                    'L', 'M', 'XL'
            ],
            'color' => [
                    'Red', 'Blue', 'Black'
            ]
        ];
                  
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
        $productURL2 = url('store/section/test/category/test/product/test');
        $productID2 = '0';
    } else {
        $currency2 = Functions::getContent($currency??'fa-usd','fa-usd');
        //Functions::getBladedString($??'','');
        //Functions::getContent($??'','');
        //Functions::getUnBladedContent($??'','');
        $image2 = Functions::getBladedString($productImage??'','');
        $imageAlt2 = Functions::getBladedString($productImageAlt??'','');
        $otherImages2 = Functions::getUnBladedContent($productOtherImages??'','');
        $productTitle2 = Functions::getBladedString($productTitle??'','');
        $productPrice2 = Functions::getBladedString($productPrice??'','');
        $productSalePrice2 = Functions::getBladedString($productSalePrice??'','');
        $productAvailability2 = Functions::getBladedString($productAvailability??'','');
        $productShortDescription2 = Functions::getBladedString($productShortDescription??'','');
        $productLongDescription2 = Functions::getBladedString($productLongDescription??'','');
        $productRating2 = Functions::getBladedString($productRating??'','');
        $productOptions2 = Functions::getUnBladedContent($productOptions??'','');
        // reviews are a Wishlist item!
        $productReviews2 = Functions::getUnBladedContent($productReviews??'','');
        $productAdditionalInfo2 = Functions::getUnBladedContent($productAdditionalInfo??'','');
        $productSticker2 = Functions::getBladedString($productSticker??'','');
        $productURL2 = Functions::getBladedString($productURL??'','');
        $productID2 = Functions::getBladedString($productID??'','');
    }

    // it does not matter to us how we get this number ...
    $numProductReviews = count($productReviews2);
        
    
    
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
                        @foreach ($productOptions2 as $key => $item)
                            <div class="pull-left">
                                <label class="control-label">{!! title_case($key) !!}:</label>
                                <select id="{{ $key . '-product-option' }}" class="form-control input-sm">
                                    @foreach ($item as $val => $option)
                                        <option value="{{ $val }}">{!! $option !!}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach
                    
                  </div>
                  <div class="product-page-cart" data-product-id="{{ $productID2 }}">
                    <div class="product-quantity">
                        <input id="product-quantity" type="text" value="1" readonly class="form-control input-sm">
                    </div>
                    <button class="btn btn-primary addToCart" type="submit" id="mainProductAdder">Add to cart</button>
                    <button class="btn btn-primary orderNow" type="submit" id="mainProductOrder">Order Now!</button>
                  </div>
                  <div class="review">
                    <input type="range" value="{{ $productRating2 }}" step="0.25" id="backing4">
                    <div class="rateit" data-rateit-backingfld="#backing4" data-rateit-resetable="false" data-rateit-readonly="true" data-rateit-ispreset="true" data-rateit-min="0" data-rateit-max="5">
                    </div>
                    <a href="#Reviews">
                        @if ($numProductReviews > 1 || $numProductReviews === 0 )
                            {{ $numProductReviews }} reviews
                        @else
                            {{ $numProductReviews }} review
                        @endif
                    </a>
                    &nbsp;&nbsp;|&nbsp;&nbsp;
                    <a href="#review-form">Write a review</a>
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
                        @if (Functions::testVar($productLongDescription2))
                            {!! $productLongDescription2 !!}
                        @else
                            <p>There is no description for this product.</p>
                        @endif
                    </div>
                    <div class="tab-pane fade" id="Information">
                        @if (Functions::testVar($productAdditionalInfo2))
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
                        @else
                            <p>There is no additional information for this product.</p>
                        @endif
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
                        <form id="review-form" method="POST" action="#" class="reviews-form" role="form">
                            <h2>Write a review</h2>
                            {{ csrf_field() }}
                            <input type="hidden" name="product-id" value="{{ $productID2 }}">
                            <div class="form-group">
                            <label for="name">Name <span class="require">*</span></label>
                            <input type="text" class="form-control" id="name" name="name">
                            </div>
                            <div class="form-group">
                            <label for="email">Email <span class="require">*</label>
                            <input type="text" class="form-control" id="email" name="email">
                            </div>
                            <div class="form-group">
                            <label for="reviewSummernote">Review <span class="require">*</span></label>
                            <textarea class="form-control" rows="8" id="reviewSummernote" name="review"></textarea>
                            </div>
                            <div class="form-group">
                            <label for="backing5">Rating</label>
                            <input type="range" value="4" step="0.25" id="backing5" name="rating">
                            <div class="rateit" data-rateit-backingfld="#backing5" data-rateit-resetable="true"  data-rateit-ispreset="true" data-rateit-min="0" data-rateit-max="5">
                            </div>
                            </div>
                            <div class="padding-top-20">                  
                            <button type="submit" form="review-form" class="btn btn-primary" name="sendReview" id="sendReview">Send</button>
                            </div>
                        </form>
                        <!-- END FORM--> 
                    </div>
                  </div>
                </div>

                @if (Functions::testVar($productSticker2))
                    <div class="sticker {!! $productSticker2 !!}"></div>
                @endif
                
              </div>
            </div>
        </div>
    <!-- END CONTENT -->


@section('css-preloaded')
    @parent 
    <!-- include summernote css -->
    <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet">
@endsection

@section('js-defered')
    @parent
    <!-- include summernote js -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script>
@endsection

@section('js-extra')
    @parent
    @php    
        $optionTmp = [];
        foreach ($productOptions2 as $key => $item) {
            $optionTmp[$key] = $key . '-product-option';
        }
    @endphp
    <script>
        /** this script section is to be written here and then converted into a 
        *   Javascript file of its own and loaded here..
        **/

        jQuery(function($) 
        {
            var pageUrl = '{{ url($productURL2) }}';
            var optionSelectors = JSON.parse( '@json($optionTmp)' );
            var getOptionVals = function (options)
            {
                var result = {};
                //console.log(JSON.stringify(options));
                for(var i in options){
                    //console.log(i);
                    result[i] = $('#' + options[i]).val();
                }
                //$.extend()
                return result;
            };
            $('#reviewSummernote').summernote();
            $('.addToCart').click(function() {
                //console.log(optionSelectors);
                var data = JSON.stringify(getOptionVals(optionSelectors));
                console.log(data);
                $.ajax(
                    {
                        url: pageUrl,
                        dataType : 'json',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        type: 'POST',
                        data: getOptionVals(optionSelectors),
                        success: function(result,status,xhr){
                            console.log(status + ' -> ' + JSON.stringify(result));
                        },
                        error: function(xhr,status,error){
                            console.log(status + ' -> ' + error);
                        }
                    }
                );
            });
            $('.orderNow').click(function(){
                window.location.assign( "{{ url('checkout') }}" );
            });
            
            
            
        });
    </script>
@endsection    
