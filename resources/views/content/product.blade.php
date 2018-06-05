
@extends('content.template')
{{-- NOTE: this is a view..  --}}



@section('main-content')
    @parent

    {{-- OUR SLOTS... --}}
    @php
        $testing = true;
        $fakeData = '';

        use \App\Utilities\Functions\Functions,
            \App\Page;

        if (!$testing) {
            $sidebarMenu2 = serialize(Functions::getContent($sidebar['menu']??$fakeData,$fakeData));
            $sidebarProducts2 = Functions::getContent($sidebar['products']??$fakeData,$fakeData);
            $product2 = Functions::getContent($product??'','');
        } else {
            $sidebarMenu2 = serialize(Page::getSidebar(true));
            $sidebarProducts2 = serialize([
                [
                    'url' => 'lib/themewagon/metronicShopUI/theme/shop-item.html',
                    'img' => 'lib/themewagon/metronicShopUI/theme/assets/pages/img/products/k1.jpg',
                    'alt' => 'Some Shoes in Animal with Cut Out',
                    'price' => '31.00'
                ],
                [
                    'url' => 'lib/themewagon/metronicShopUI/theme/shop-item.html',
                    'img' => 'lib/themewagon/metronicShopUI/theme/assets/pages/img/products/k4.jpg',
                    'alt' => 'Some Shoes in Animal with Cut Out',
                    'price' => '23.00'
                ],
                [
                    'url' => 'lib/themewagon/metronicShopUI/theme/shop-item.html',
                    'img' => 'lib/themewagon/metronicShopUI/theme/assets/pages/img/products/k3.jpg',
                    'alt' => 'Some Shoes in Animal with Cut Out',
                    'price' => '86.00'
                ]
            ]);
            $product2 = [
                'productImage' => e('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model7.jpg'),
                'productImageAlt' => e('Cool green dress with red bell'),
                'productOtherImages' => serialize([
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
                ]),
                'productTitle' => e('Cool green dress with red bell'),
                'productPrice' => e('62.00'),
                'productSalePrice' => e('47.00'),
                'productAvailability' => e('In Stock'),
                'productShortDescription' => e('<p>
                                                    Lorem ipsum dolor ut sit 
                                                    ame dolore adipiscing elit, 
                                                    sed nonumy nibh sed euismod 
                                                    laoreet dolore magna aliquarm 
                                                    erat volutpat Nostrud duis 
                                                    molestie at dolore.
                                                </p>'),
                'productLongDescription' => e('<p>
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
                                                </p>'),
                'productRating' => e('4'),
                'productOptions' => serialize([
                                        'size' => [
                                                'L', 'M', 'XL'
                                        ],
                                        'color' => [
                                                'Red', 'Blue', 'Black'
                                        ]
                                    ]),
                // REMEMBER: reviews are a Wishlist item!
                'productReviews' => serialize([
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
                        ]),
                'productAdditionalInfo' => serialize([
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
                            ]),
                'productSticker' => e('sticker-sale'),
                'productURL' => e(url('store/section/test/category/test/product/test')),
                'productID' => e('0'),
            ];
        }
        //dd($sidebarMenu2, $sidebarProducts2);
        $currency2 = Functions::getContent($currency??'fa-usd','fa-usd');

    @endphp

    <!-- BEGIN SIDEBAR & CONTENT -->
        <div class="row margin-bottom-40">
            @component('lib.themewagon.sidebar')
                @slot('menu')
                    {!! $sidebarMenu2 !!}
                @endslot
                {{-- ignoring the 'filters' slot to omit it.. --}}
                @slot('products')
                    {!! $sidebarProducts2 !!}
                @endslot
                @slot('currency')
                    {!! $currency2 !!}
                @endslot
            @endcomponent

            @component('lib.themewagon.product_full_item')

                @slot('currency')
                    {!! $currency2 !!}
                @endslot

                @foreach ($product2 as $key => $item)
                    @slot($key)
                        {!! $item !!}
                    @endslot
                @endforeach
                
            @endcomponent

        </div>
    <!-- END SIDEBAR & CONTENT -->

@endsection
