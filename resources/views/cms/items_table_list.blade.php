
@php
$testing = false;
use \App\Utilities\Functions\Functions;
use \App\Page;
use \Illuminate\Support\HtmlString;

// The DATA for the SLOTS of THIS COMPONENT are gathered HERE!!!  
// Note: they CAN be empty... 

$items2 = Functions::getUnBladedContent($items??'', []);
//dd($products, $products2);
$currency2 = Functions::getBladedString($currency??'fa-usd','fa-usd');
$sorting2 = Functions::getBladedContent($sorting??'', '');
$pageNumber2 = intval(Functions::getBladedString($pageNumber??0,0));
// our default.. is 12 products per page (the template had 9..)
$itemsPerPage2 = intval(Functions::getBladedString($itemsPerPage??12,12));

$extraContainerCss2 = Functions::getBladedString($extraContainerCss??'', '');
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
<div class="col-md-9 col-sm-7 {{ $extraContainerCss2 }}">

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
                $firstIdx = intval($paginator2['firstItemIndex']);
                $lastIdx = intval($paginator2['lastItemIndex']);
                $trntmp = Functions::genRange($firstIdx, $lastIdx, 1);
                /* dd([ 
                    'paginator' => $paginator2, 
                    'currentPage' => $currentPage,
                    'Items' => $items2,
                    'TranslatorArr' => $trntmp
                    ]
                ); */
                /*
                    @php
                        if (array_key_exists($idx, $items2)) {
                            $idx2 = $idx;
                        } else {
                            $idx2 = Functions::getIndexOf($trntmp, $idx);
                        }
                    @endphp
                */
            @endphp
            <div class="table-wrapper-responsive">
                <table>

                
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

                        @foreach ($items2 as $item)
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
                                            @if (Functions::isPropKeyIn($button, 'icon'))
                                                <i class="fa {{ $button['icon'] }}">
                                            @endif
                                            @if (Functions::isPropKeyIn($button, 'text'))
                                                {!! $button['text'] !!}
                                            @endif
                                        </a>
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                    
                    </tbody>

                    @foreach ($items2 as $item)

                        @php
                            //dd($row);
                        @endphp
                        
                            @foreach ($row as $idx)

                                @php
                                    //dd($idx);
                                    // $idx2 = $idx - 1;
                                    if (array_key_exists($idx, $items2)) {
                                        $idx2 = $idx;
                                    } else {
                                        $idx2 = Functions::getIndexOf($trntmp, $idx);
                                    }
                                @endphp

                                @component($component2)
                                    {{-- 
                                        slot 'extraOuterCss' added above... NOPE!!!
                                    
                                        @slot('extraOuterCss')
                                            {{ "col-md-4 col-sm-6 col-xs-12" }}
                                        @endslot

                                    --}}
                                    @if (!array_key_exists('extraOuterCss', $items2[$idx2]) 
                                        || empty($items2[$idx2]['extraOuterCss']))
                                        @slot('extraOuterCss')
                                            {{ "col-md-4 col-sm-6 col-xs-12" }}
                                        @endslot
                                    @endif
                                    @foreach ($items2[$idx2] as $key => $value)
                                        @slot($key)
                                            {{ $value }}
                                        @endslot
                                    @endforeach
                                    @slot('currency')
                                        {!! $currency2 !!}
                                    @endslot
                                    @slot('type')
                                        {!! $type2 !!}
                                    @endslot

                                @endcomponent
                            @endforeach
                        
                    @endforeach

                </table>
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