
@php
    $testing = false;
    use \App\Utilities\Functions\Functions,
        \App\Page;

    // The DATA for the SLOTS of THIS COMPONENT are gathered HERE!!!  
    // Note: they CAN be empty... 

    $items2 = Functions::getUnBladedContent($items??'', []);
    //dd($products, $products2);
    $currency2 = Functions::getBladedString($currency??'fa-usd','fa-usd');
    $sorting2 = Functions::getBladedContent($sorting??'', '');
    $pageNumber2 = intval(Functions::getBladedString($pageNumber??0,0));
    // our default.. is 12 products per page (the template had 9..)
    $itemsPerPage2 = intval(Functions::getBladedString($itemsPerPage??12,12));

    $containerCss2 = Functions::getBladedString($containerCss??'', 'col-md-9 col-sm-7');
    $type2 = Functions::getBladedString($type??'', 'Items');

    $paginator2 = Functions::getBladedContent($paginator??[], []);

    // NOTE: every product 'row' can hold up to 3 products! 
    // $itemsPerRow2 = getBladedContent($itemsPerRow,3);
    $itemsPerRow2 = 1;

    $useVuePaginator = false;
    $useOldPagination = false;

    // Some Utility Functions for the component..

    if(Functions::testVar($items2)){
        // Initializing the row Indices while we are at it..
        $totalItems = Functions::testVar($paginator2)
        ? intval($paginator2['totalItems']) : count($items2);
        $numPages = Functions::testVar($paginator2)
        ? intval($paginator2['totalNumPages']) : 0;
        // Functions::genRowsPerPage($totalProducts, $productsPerPage2);
        /// $currentPage is an index into $rowIdxs, which will only contain
        ///  more than ONE element (an array) if $pageNumber2 was equal to -2!
        $currentPage = $pageNumber2 > -2 ? 0 : random_int(0, $numPages-1); 
        /// the old code -> $pageNumber2 > -1 ? $pageNumber2 : 0;
                            
        // if $productsPerPage is set then 
        //  EVEN IF $pageNumber2 IS NOT set then we have paganation!
        // THOUGH REALLY if we have more products than 
        //  $productsPerRow times $rowsPerPage then we have paganation
        // regardless...
        if (false) {
            dd(
                [
                    'itemsPerPage' => $itemsPerPage2, 
                    'itemsPerRow' => $itemsPerRow2, 
                    'totalItems' => $totalItems, 
                    'pageNumber' => $pageNumber2, 
                    'numPages' => $numPages, 
                    'currentPage' => $currentPage
                ]
            );
        }

        if (!Functions::testVar($paginator2) && $pageNumber2 > -1) {
            // initializing the paginator
            $numPagesPerPagingView = 4;
            $viewNumber = 0;
            $baseUrl = '';
            $pagingFor = '';
            $pageNumber3 = $pageNumber2 > -1 ? $pageNumber2 : 0;
            if ($useVuePaginator) {
                $pageNumber3 = $pageNumber3  + 1;
            } 
            $paginator2 = Page::genPagination2(
                $pageNumber3, $itemsPerPage2, $totalItems, 
                $numPagesPerPagingView, $pagingFor, $viewNumber, 
                $baseUrl
            );
        } 
        //dd("cont", $paginator, $currentPage, $items2);
            
    }
    //dd("cont", $paginator, $paginator2, $items2);


       
@endphp

<!-- BEGIN CONTENT -->
    <div class="{{ $containerCss2 }}">

        @if (Functions::testVar($sorting2))
        @else

            <div class="row list-view-sorting clearfix">
                @if(false)
                    {{-- 
                        This merely provides different views of the content, 
                        THIS IS AN WISHLIST_TASK_LIST Item => NOT YET IMPLEMENTED!
                    --}}
                    
                    <div class="col-md-2 col-sm-2 list-view">
                        <a href="javascript:;"><i class="fa fa-th-large"></i></a>
                        <a href="javascript:;"><i class="fa fa-th-list"></i></a>
                    </div>
                @endif
                <div class="col-md-10 col-sm-10">
                    <div class="pull-right">
                        <label class="control-label">Show:</label>
                        <select class="form-control input-sm">
                            <option value="#?limit=none" selected="selected">All</option>
                            <option value="#?limit=12">12</option>
                            <option value="#?limit=25">25</option>
                            <option value="#?limit=50">50</option>
                            <option value="#?limit=75">75</option>
                            <option value="#?limit=100">100</option>
                        </select>
                    </div>
                    <div class="pull-right">
                        <label class="control-label">Sort&nbsp;By:</label>
                        <select class="form-control input-sm">
                            <option value="#?sort=default&amp;order=ASC" selected="selected">Default</option>
                            <option value="#?sort=name&amp;order=ASC">Name (A - Z)</option>
                            <option value="#?sort=name&amp;order=DESC">Name (Z - A)</option>
                            <option value="#?sort=price&amp;order=ASC">Price (Low &gt; High)</option>
                            <option value="#?sort=price&amp;order=DESC">Price (High &gt; Low)</option>
                            <option value="#?sort=rating&amp;order=DESC">Rating (Highest)</option>
                            <option value="#?sort=rating&amp;order=ASC">Rating (Lowest)</option>
                        </select>
                    </div>
                </div>
            </div>
            
        @endif


        <!-- BEGIN PRODUCT LIST -->
            @if(Functions::testVar($items2))

                @php
                    //dd($rowIdxs);
                    //dd($rowIdxs[$currentPage]);
                    /*
                        $firstIdx = intval($paginator2['firstItemIndex']);
                        $lastIdx = intval($paginator2['lastItemIndex']);
                        $trntmp = Functions::genRange($firstIdx, $lastIdx, 1);
                        dd([ 
                            'paginator' => $paginator2, 
                            'currentPage' => $currentPage,
                            'Items' => $items2,
                            'TranslatorArr' => $trntmp
                            ]
                        ); 
                    */
                @endphp

                <div class="row">
                    <div class="table-wrapper-responsive">
                        <table>

                        
                            <thead>
                                <tr>
                                    <th class="goods-page-image">Image</th>
                                    <th class="goods-page-description">Description</th>
                                    @if (Functions::isPropKeyIn($items2[0], 'parent'))
                                        <th class="goods-page-ref-no">Parent</th>
                                    @endif
                                    @if (Functions::isPropKeyIn($items2[0], 'quantity'))
                                        <th class="goods-page-quantity">Quantity</th>
                                    @endif
                                    @if (Functions::isPropKeyIn($items2[0], 'order'))
                                        <th class="goods-page-quantity">Order</th>
                                    @endif
                                    @if (Functions::isPropKeyIn($items2[0], 'price'))
                                        <th class="goods-page-price">Unit price</th>
                                    @endif
                                    @if (Functions::isPropKeyIn($items2[0], 'sale'))
                                        <th class="goods-page-price">Unit sale</th>
                                    @endif
                                    <th class="goods-page-description" colspan="3">Dates</th>
                                    @if (Functions::isPropKeyIn($items2[0], 'subtotal'))
                                        <th class="goods-page-total" colspan="2">Total</th>
                                    @elseif (Functions::isPropKeyIn($items2[0], 'total'))
                                        <th class="goods-page-total" colspan="2">Total</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($items2 as $item)
                                    <tr>
                                        <td class="goods-page-image">
                                            <a href="{{ url($item['url']) }}">
                                                <img src="{{ asset($item['img']['img']) }}" alt="{{ $item['name'] }}">
                                            </a>
                                        </td>
                                        <td class="goods-page-description">
                                            <h3>
                                                <a href="{{ url($item['url']) }}">
                                                    {{ $item['title'] ?? $item['name'] }}
                                                </a>
                                            </h3>
                                            @if (Functions::isPropKeyIn($item, 'description'))
                                                <p>
                                                    {{ $item['description'] }}
                                                </p>
                                            @endif
                                            @if (Functions::isPropKeyIn($item, 'article'))
                                                <p>
                                                    {{ $item['article'] }}
                                                </p>
                                            @endif
                                            @if (Functions::isPropKeyIn($item, 'payload'))
                                                <p>
                                                    @foreach ($item['payload'] as $optName => $optVal)
                                                        {{ $optName }} : {{ $optVal }} 
                                                        @if (! $loop->last)
                                                            <hr>
                                                        @endif
                                                    @endforeach
                                                </p>
                                            @endif
                                            @if (false)
                                                <p>
                                                    <strong>Item {{ $loop->index + 1 }}</strong> - 
                                                    <hr>
                                                    @foreach ($item['options'] as $optName => $optVal)
                                                        {{ $optName }}: {{ $optVal }}
                                                    @endforeach
                                                </p>
                                                <em>More info is here</em>
                                            @endif
                                            
                                        </td>
                                        @if (Functions::isPropKeyIn($item, 'parent'))
                                            <td class="goods-page-ref-no">
                                                <a href="{{ url($item['parent']['url']) }}">
                                                    {{ $item['parent']['name'] }}
                                                </a>
                                            </td>
                                        @endif
                                        @if (Functions::isPropKeyIn($item, 'quantity'))
                                            <td class="goods-page-quantity">
                                                <div class="product-quantity">
                                                    <input id="product-quantity" type="text" value="{{ $item['quantity'] }}" class="form-control input-sm">
                                                </div>
                                            </td>
                                        @endif
                                        @if (Functions::isPropKeyIn($item, 'order'))
                                            <td class="goods-page-quantity">
                                                <p>
                                                    {{ $item['order'] }}
                                                </p>
                                            </td>
                                        @endif
                                        @if (Functions::isPropKeyIn($item, 'price'))
                                            <td class="goods-page-price">
                                                <strong>
                                                    <span><i class="fa {{ $currency2 }}"></i></span>
                                                    {{ $item['price'] }}
                                                </strong>
                                            </td>
                                        @endif
                                        @if (Functions::isPropKeyIn($item, 'sale'))
                                            <td class="goods-page-price">
                                                <strong>
                                                    <span><i class="fa {{ $currency2 }}"></i></span>
                                                    {{ $item['sale'] }}
                                                </strong>
                                            </td>
                                        @endif
                                        <td class="goods-page-description">
                                            <p>
                                                @foreach ($item['dates'] as $t => $d)
                                                    {{ $t }} : {{ $d }}
                                                    @if (! $loop->last)
                                                        <hr>
                                                    @endif
                                                @endforeach
                                            </p>
                                        </td>
                                        @if (Functions::isPropKeyIn($item, 'subtotal'))
                                            <td class="goods-page-total">
                                                <strong>
                                                    <span><i class="fa {{ $currency2 }}"></i></span>
                                                    {{ $item['subtotal'] }}
                                                </strong>
                                            </td>
                                        @elseif (Functions::isPropKeyIn($items2[0], 'total'))
                                            <td class="goods-page-total">
                                                <strong>
                                                    <span><i class="fa {{ $currency2 }}"></i></span>
                                                    {{ $item['total'] }}
                                                </strong>
                                            </td>
                                        @endif
                                        <td class="del-goods-col">
                                            <a href="{{ url($item['img']['img']) }}" class="btn btn-default fancybox.image fancybox-button">Zoom Image</a>
                                            <a class="btn btn-warning" href="{{ url($item['url'] . '/edit') }}">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                Edit
                                            </a>
                                            <a class="btn btn-danger" href="{{ url($item['url'] . '/delete') }}">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                                Delete
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            
                            </tbody>

                        </table>
                    </div>
                </div>
                
            @elseif ($testing)

            

                @if (false)
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
                @endif



            @else
                
                
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="well">
                            <h3>
                                <i class="fa fa-exclamation-triangle"></i>
                                <strong>We are Sorry!</strong>
                                We Have no {{ $type2 }} to Display!
                            </h3>
                        </div>
                    </div>
                </div>
                

            @endif
        <!-- END PRODUCT LIST -->

        @if (Functions::testVar($items2) && Functions::testVar($paginator2))
            @if ($useVuePaginator)
                <div id="masterPagination"></div>
                <script>
                    window.Laravel.pagination = '@json($paginator2)';
                </script>
            @else
                @component('lib.themewagon.paginator')
                    @foreach ($paginator2 as $key => $val)
                        @slot($key)
                            {!! Functions::toBladableContent($val) !!}
                        @endslot
                    @endforeach
                @endcomponent
            @endif
        @endif

    </div>
<!-- END CONTENT -->