
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
    $pageNumber2 = 0; // intval(Functions::getBladedString($pageNumber??0,0));
    // our default.. is 12 products per page (the template had 9..)
    $itemsPerPage2 = intval(Functions::getBladedString($itemsPerPage??12,12));

    // NOTE: every product 'row' can hold up to 3 products! 
    // $itemsPerRow2 = getBladedContent($itemsPerRow,3);
    $itemsPerRow2 = 3;

    $useVuePaginator = false;
    $useOldPagination = false;

    // Some Utility Functions for the component..

    if(Functions::testVar($items2)){

        // adding the 'extraOuterCss' slot & data to the array..
        // so that we can use 'product_gallery.blade.php' for this..
        /// HAD TO RESTORE THE SLOTS BELOW>>>!!!
        /*
            foreach ($products2 as $value){
                if(Functions::testVar($value) && is_array($value) 
                && !array_key_exists('extraOuterCss',$value)){
                    $value['extraOuterCss'] = "col-md-4 col-sm-6 col-xs-12";
                }
            }
        */

        // Initializing the row Indices while we are at it..
        $totalItems = count($items2);
        $numPages = 0;
        // Functions::genRowsPerPage($totalProducts, $productsPerPage2);
        $rowIdxs = Functions::genPagesIndexes(
            $itemsPerPage2, $itemsPerRow2, 
            $totalItems, $pageNumber2, $numPages
        );
        $currentPage = $pageNumber2 > -1 ? $pageNumber2 : 0;
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
                    'rowIdxs' => $rowIdxs,
                    'currentPage' => $currentPage
                ]
            );
        }

        if ($pageNumber2 > -1) {
            // initializing the paginator
            $numPagesPerPagingView = 4;
            $viewNumber = 0;
            $baseUrl = '';
            if ($useOldPagination) {
                //dd(
                //    count($rowIdxs[0]),
                //    $rowIdxs[0][count($rowIdxs[0]) -1],
                //    $rowIdxs[0][count($rowIdxs[0]) -1][count($rowIdxs[0][count($rowIdxs[0]) -1]) -1]
                //);
                //dd($rowIdxs[0][count($rowIdxs[0])][count($rowIdxs[0][count($rowIdxs[0])])]);
                $firstItem = $rowIdxs[0][0][0];
                $lastItem = $rowIdxs[0][count($rowIdxs[0]) -1][count($rowIdxs[0][count($rowIdxs[0]) -1]) -1];
                $ranges = Functions::genRange(0, $numPages - 1, 1);
                $paginator = [
                    'totalItems' => $totalItems,
                    'numRanges' => $numPages,
                    'ranges' => $ranges,
                    'currentRange' => [
                        'begin' => $firstItem,
                        'end' => $lastItem,
                        'index' => $pageNumber2 > -1 ? $pageNumber2 : 0,
                    ],
                ];
                $paginator2 = Page::genPagination(
                    $pageNumber2 > -1 ? $pageNumber2 : 0, 
                    $firstItem, $lastItem,
                    $totalItems, $ranges, $numPagesPerPagingView ,
                    '', $viewNumber, $baseUrl
                );
            } else {
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
        } else {
            $paginator2 = [];
        }
        //dd("cont", $paginator, $currentPage, $items2);
            
    } else {
        $paginator2 = [];
    }
    //dd("cont", $paginator, $items2);

    if (false) {
        $numPagesPerPagingView = 4;
        $viewNumber = 0;
        $baseUrl = '';
        $pagingFor = '';
        $pageNumber3 = $pageNumber2 > -1 ? $pageNumber2 : 0;
        if ($useVuePaginator) {
            $pageNumber3 = $pageNumber3  + 1;
        } 
        
        if ($useOldPagination) {
            $paginator2 = Page::genPagination(
                $pageNumber3, $firstItem, $lastItem,
                $totalItems, $ranges, $numPagesPerPagingView ,
                '', $viewNumber, $baseUrl
            );
        } else {
            /*
                genPagination2(
                    int $pageNum, int $numItemsShownOnPage, int $totalItems, 
                    int $numPageLinksPerPagingView = 4, string $pagingFor = '', 
                    int $viewNumber = 0, string $baseUrl = ''
                )
            */
            $paginator2 = Page::genPagination2(
                $pageNumber3, $itemsPerPage2, $totalItems, 
                $numPagesPerPagingView, $pagingFor, 
                $viewNumber, $baseUrl
            );
        }
    }
    //dd($paginator2);
    
           
@endphp

<!-- BEGIN CONTENT -->
<div class="col-md-9 col-sm-7">

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
            //dd($rowIdxs[$currentPage]);
        @endphp
        
        @foreach ($rowIdxs[$currentPage] as $row)

            @php
                //dd($row);
            @endphp

            <div class="row product-list">
                @foreach ($row as $idx)

                    @php
                        //dd($idx);
                        // $idx2 = $idx - 1;
                    @endphp

                    @if (array_key_exists($idx, $items2))

                        @component('lib.themewagon.product_mini')
                            {{-- 
                                slot 'extraOuterCss' added above... NOPE!!!
                            
                                @slot('extraOuterCss')
                                    {{ "col-md-4 col-sm-6 col-xs-12" }}
                                @endslot

                            --}}
                            @if (!array_key_exists('extraOuterCss', $items2[$idx]) 
                                || empty($items2[$idx]['extraOuterCss']))
                                @slot('extraOuterCss')
                                    {{ "col-md-4 col-sm-6 col-xs-12" }}
                                @endslot
                            @endif
                            @foreach ($items2[$idx] as $key => $value)
                                @slot($key)
                                    {{ $value }}
                                @endslot
                            @endforeach
                            @slot('currency')
                                {!! $currency2 !!}
                            @endslot

                        @endcomponent

                    @endif
                @endforeach
            </div>
            
        @endforeach
        
    @elseif ($testing)

        <div class="row product-list">
            <!-- PRODUCT ITEM START -->
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="product-item">
                    <div class="pi-img-wrapper">
                    <img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model1.jpg') }}" class="img-responsive" alt="Berry Lace Dress">
                    <div>
                        <a href="{{ url('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model1.jpg') }}" class="btn btn-default fancybox-button">Zoom</a>
                        <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                    </div>
                    </div>
                    <h3><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Berry Lace Dress Berry Lace Dress</a></h3>
                    <div class="pi-price">$29.00</div>
                    <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                </div>
            </div>
            <!-- PRODUCT ITEM END -->
            <!-- PRODUCT ITEM START -->
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="product-item">
                    <div class="pi-img-wrapper">
                    <img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model2.jpg') }}" class="img-responsive" alt="Berry Lace Dress">
                    <div>
                        <a href="{{ url('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model2.jpg') }}" class="btn btn-default fancybox-button">Zoom</a>
                        <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                    </div>
                    </div>
                    <h3><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Berry Lace Dress</a></h3>
                    <div class="pi-price">$29.00</div>
                    <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                </div>
            </div>
            <!-- PRODUCT ITEM END -->
            <!-- PRODUCT ITEM START -->
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="product-item">
                    <div class="pi-img-wrapper">
                    <img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model6.jpg') }}" class="img-responsive" alt="Berry Lace Dress">
                    <div>
                        <a href="{{ url('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model6.jpg') }}" class="btn btn-default fancybox-button">Zoom</a>
                        <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                    </div>
                    </div>
                    <h3><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Berry Lace Dress 2</a></h3>
                    <div class="pi-price">$29.00</div>
                    <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                </div>
            </div>              
            <!-- PRODUCT ITEM END -->
        </div>
        <div class="row product-list">
            <!-- PRODUCT ITEM START -->
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="product-item">
                    <div class="pi-img-wrapper">
                    <img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model4.jpg') }}" class="img-responsive" alt="Berry Lace Dress">
                    <div>
                        <a href="{{ url('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model4.jpg') }}" class="btn btn-default fancybox-button">Zoom</a>
                        <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                    </div>
                    </div>
                    <h3><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Berry Lace Dress Berry Lace Dress</a></h3>
                    <div class="pi-price">$29.00</div>
                    <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                </div>
            </div>
            <!-- PRODUCT ITEM END -->
            <!-- PRODUCT ITEM START -->
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="product-item">
                    <div class="pi-img-wrapper">
                    <img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model5.jpg') }}" class="img-responsive" alt="Berry Lace Dress">
                    <div>
                        <a href="{{ url('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model5.jpg') }}" class="btn btn-default fancybox-button">Zoom</a>
                        <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                    </div>
                    </div>
                    <h3><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Berry Lace Dress</a></h3>
                    <div class="pi-price">$29.00</div>
                    <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                    <div class="sticker sticker-new"></div>
                </div>
            </div>
            <!-- PRODUCT ITEM END -->
            <!-- PRODUCT ITEM START -->
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="product-item">
                    <div class="pi-img-wrapper">
                    <img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model3.jpg') }}" class="img-responsive" alt="Berry Lace Dress">
                    <div>
                        <a href="{{ url('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model3.jpg') }}" class="btn btn-default fancybox-button">Zoom</a>
                        <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                    </div>
                    </div>
                    <h3><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Berry Lace Dress</a></h3>
                    <div class="pi-price">$29.00</div>
                    <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                </div>
            </div>              
            <!-- PRODUCT ITEM END -->
        </div>
        <div class="row product-list">
            <!-- PRODUCT ITEM START -->
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="product-item">
                    <div class="pi-img-wrapper">
                    <img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model7.jpg') }}" class="img-responsive" alt="Berry Lace Dress">
                    <div>
                        <a href="{{ url('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model7.jpg')}}" class="btn btn-default fancybox-button">Zoom</a>
                        <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                    </div>
                    </div>
                    <h3><a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html')}}">Berry Lace Dress</a></h3>
                    <div class="pi-price">$29.00</div>
                    <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                </div>
            </div>
            <!-- PRODUCT ITEM END -->
            <!-- PRODUCT ITEM START -->
            <div class="col-md-4 col-sm-6 col-xs-12">
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
                </div>
            </div>
            <!-- PRODUCT ITEM END -->
            <!-- PRODUCT ITEM START -->
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="product-item">
                    <div class="pi-img-wrapper">
                    <img src="{{url('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model2.jpg')}}" class="img-responsive" alt="Berry Lace Dress">
                    <div>
                        <a href="{{url('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model2.jpg')}}" class="btn btn-default fancybox-button">Zoom</a>
                        <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                    </div>
                    </div>
                    <h3><a href="{{url('lib/themewagon/metronicShopUI/theme/shop-item.html')}}">Berry Lace Dress</a></h3>
                    <div class="pi-price">$29.00</div>
                    <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                    <div class="sticker sticker-sale"></div>
                </div>
            </div>
            <!-- PRODUCT ITEM END -->
        </div>

    @else
        
        
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
                <div class="well">
                    <h3>
                        <i class="fa fa-exclamation-triangle"></i>
                        <strong>We are Sorry!</strong>
                        We Have no Items to Display!
                    </h3>
                </div>
            </div>
        </div>
        

    @endif
    <!-- END PRODUCT LIST -->

    @if (Functions::testVar($paginator2))
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