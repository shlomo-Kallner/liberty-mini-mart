
@php
    $testing = true;
    use \App\Utilities\Functions\Functions;

    if ($testing) {
        $cart2 = [
            [
                'url' => 'javascript:;',
                'img' => 'lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model3.jpg',
                'imgAlt' => 'Berry Lace Dress',
                'shortDescription' => 'Cool green dress with red bell',
                'options' => [
                    'Color' => 'Green',
                    'Size' => 'S'
                ],
                'refNo' => 'javc2133',
                'quantity' => '1',
                'price' => '47.00',
                'subtotal' => '47.00',
                'buttons' => [
                    [
                        'class' => 'del-goods',
                        'text' => '&nbsp;',
                        'url' => 'javascript:;',
                        'icon' => '',
                    ],
                    [
                        'class' => 'add-goods',
                        'text' => '&nbsp;',
                        'url' => 'javascript:;',
                        'icon' => '',
                    ]
                ],
            ],
            [
                'url' => 'javascript:;',
                'img' => 'lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model4.jpg',
                'imgAlt' => 'Berry Lace Dress',
                'shortDescription' => 'Cool green dress with red bell',
                'options' => [
                    'Color' => 'Green',
                    'Size' => 'S'
                ],
                'refNo' => 'javc2133',
                'quantity' => '1',
                'price' => '47.00',
                'subtotal' => '47.00',
                'buttons' => [
                    [
                        'class' => 'del-goods',
                        'text' => '&nbsp;',
                        'url' => 'javascript:;',
                        'icon' => '',
                    ],
                    [
                        'class' => 'add-goods',
                        'text' => '&nbsp;',
                        'url' => 'javascript:;',
                        'icon' => '',
                    ]
                ],
            ]
        ];
        $subTotal2 = '47.00';
        $shippingCost2 = '3.00';
        $totalPrice2 = '50.00';
        $currency2 = 'fa-usd';
        $cartTitle2 = 'Your Cart&apos;s Content:';
        $cartType2 = 'shopping cart';
        $extraContainerCss2 = 'col-sm-12';
        $pageButtons2 = [
            [
                'class' => 'btn-default',
                'text' => 'Continue shopping',
                'icon' => 'fa-shopping-cart',
            ],
            [
                'class' => 'btn-primary',
                'text' => 'Checkout',
                'icon' => 'fa-check',
            ]
        ];
    } else {
        $cart2 = [
            [
                'url' => 'javascript:;',
                'img' => 'lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model3.jpg',
                'imgAlt' => 'Berry Lace Dress',
                'shortDescription' => 'Cool green dress with red bell',
                'options' => [
                    'Color' => 'Green',
                    'Size' => 'S'
                ],
                'refNo' => 'javc2133',
                'quantity' => '1',
                'price' => '47.00',
                'subtotal' => '47.00',
                'buttons' => [
                    [
                        'class' => 'del-goods',
                        'text' => '&nbsp;',
                        'url' => 'javascript:;',
                        'icon' => '',
                    ],
                    [
                        'class' => 'add-goods',
                        'text' => '&nbsp;',
                        'url' => 'javascript:;',
                        'icon' => '',
                    ]
                ],
            ],
            [
                'url' => 'javascript:;',
                'img' => 'lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model4.jpg',
                'imgAlt' => 'Berry Lace Dress',
                'shortDescription' => 'Cool green dress with red bell',
                'options' => [
                    'Color' => 'Green',
                    'Size' => 'S'
                ],
                'refNo' => 'javc2133',
                'quantity' => '1',
                'price' => '47.00',
                'subtotal' => '47.00',
                'buttons' => [
                    [
                        'class' => 'del-goods',
                        'text' => '&nbsp;',
                        'url' => 'javascript:;',
                        'icon' => '',
                    ],
                    [
                        'class' => 'add-goods',
                        'text' => '&nbsp;',
                        'url' => 'javascript:;',
                        'icon' => '',
                    ]
                ],
            ]
        ];
        $subTotal2 = '47.00';
        $shippingCost2 = '3.00';
        $totalPrice2 = '50.00';
        $currency2 = 'fa-usd';
        $cartTitle2 = 'Your Cart&apos;s Content:';
        $cartType2 = 'shopping cart';
        $extraContainerCss2 = 'col-sm-12';
        $pageButtons2 = [
            [
                'class' => 'btn-default',
                'text' => 'Continue shopping',
                'icon' => 'fa-shopping-cart',
                'url' => 'javascript:;',
            ],
            [
                'class' => 'btn-primary',
                'text' => 'Checkout',
                'icon' => 'fa-check',
                'url' => 'javascript:;',
            ]
        ];
    }
        

@endphp

<!-- BEGIN CONTENT -->
    <div class="col-md-12 {{ $extraContainerCss2 }}">
        <h1>{!! $cartTitle2 !!}</h1>
        <div class="goods-page">
            <div class="goods-data clearfix">
                @if (Functions::testVar($cart2))
                    <div class="table-wrapper-responsive">
                        <table summary="Shopping cart">
                            
                            <thead>
                                <tr>
                                    <th class="goods-page-image">Image</th>
                                    <th class="goods-page-description">Description</th>
                                    <th class="goods-page-ref-no">Ref No</th>
                                    <th class="goods-page-quantity">Quantity</th>
                                    <th class="goods-page-price">Unit price</th>
                                    <th class="goods-page-total" colspan="2">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cart2 as $item)
                                    <tr>
                                        <td class="goods-page-image">
                                            <a href="{{ url($item['url']) }}">
                                                <img src="{{ asset($item['img']) }}" alt="{{ $item['imgAlt'] }}">
                                            </a>
                                        </td>
                                        <td class="goods-page-description">
                                            <h3>
                                                <a href="{{ url($item['url']) }}">{{ $item['shortDescription'] }}</a>
                                            </h3>
                                            <p>
                                                <strong>Item {{ $loop->index + 1 }}</strong> - 
                                                @foreach ($item['options'] as $optName => $optVal)
                                                    {{ $optName }}: {{ $optVal }}
                                                @endforeach
                                            </p>
                                            {{-- <em>More info is here</em> --}}
                                            
                                        </td>
                                        <td class="goods-page-ref-no">
                                                {{ $item['refNo'] }}
                                        </td>
                                        <td class="goods-page-quantity">
                                            <div class="product-quantity">
                                                <input id="product-quantity" type="text" value="{{ $item['quantity'] }}" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td class="goods-page-price">
                                            <strong>
                                                <span><i class="fa {{ $currency2 }}"></i></span>
                                                {{ $item['price'] }}
                                            </strong>
                                        </td>
                                        <td class="goods-page-total">
                                            <strong>
                                                <span><i class="fa {{ $currency2 }}"></i></span>
                                                {{ $item['subtotal'] }}
                                            </strong>
                                        </td>
                                        <td class="del-goods-col">
                                            @foreach ($item['buttons'] as $button)
                                                <a class="{{ $button['class'] }}" href="{{ url($button['url']) }}">
                                                    @if (Functions::isPropKeyIn($button, 'text'))
                                                        {!! $button['text'] !!}
                                                    @endif
                                                </a>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                            
                            </tbody>

                        </table>
                    </div>
                @else
                    <p>Your {{ $cartType2 }} is empty!</p>
                @endif
                
                <div class="shopping-total">
                    <ul>
                        <li>
                            <em>Sub total</em>
                            <strong class="price">
                                <span><i class="fa {{ $currency2 }}"></i></span>
                                {{ $subTotal2 }}
                            </strong>
                        </li>
                        <li>
                            <em>Shipping cost</em>
                            <strong class="price">
                                <span><i class="fa {{ $currency2 }}"></i></span>
                                {{ $shippingCost2 }}
                            </strong>
                        </li>
                        <li class="shopping-total-price">
                            <em>Total</em>
                            <strong class="price">
                                <span><i class="fa {{ $currency2 }}"></i></span>
                                {{ $totalPrice2 }}
                            </strong>
                        </li>
                    </ul>
                </div>
            </div>
            
            @foreach ($pageButtons2 as $button)
                @if (true)
                    <button class="btn {{ $button['class'] }}" type="submit">
                        {{ $button['text'] }} <i class="fa {{ $button['icon'] }}"></i>
                    </button>
                @else
                    <a class="btn {{ $button['class'] }}" href="{{ $button['url']??'#' }}" role="button">
                        {{ $button['text'] }} <i class="fa {{ $button['icon'] }}"></i>
                    </a>
                @endif
            @endforeach
        </div>
    </div>
<!-- END CONTENT -->

