
@php
    $testing = false;
    use \App\Utilities\Functions\Functions;
    use \App\Page;
    use \Illuminate\Support\HtmlString;
    use Illuminate\Contracts\Support\Htmlable;

    // The DATA for the SLOTS of THIS COMPONENT are gathered HERE!!!  
    // Note: they CAN be empty... 

    $products2 = Functions::getUnBladedContent($products??'', []);
    //dd($products, $products2);
    $currency2 = Functions::getBladedString($currency??'fa-usd','fa-usd');
    $sorting2 = Functions::getBladedContent($sorting??'', '');
    $pageNumber2 = 0; // intval(Functions::getBladedString($pageNumber??0,0));
    // our default.. is 12 products per page (the template had 9..)
    $productsPerPage2 = intval(Functions::getBladedString($productsPerPage??12,12));

    // NOTE: every product 'row' can hold up to 3 products! 
    // $productsPerRow2 = getBladedContent($productsPerRow,3);
    $productsPerRow2 = 3;

    // Some Utility Functions for the component..

    if(Functions::testVar($products2)){

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
        $totalProducts = count($products2);
        $numPages = 0;
        // Functions::genRowsPerPage($totalProducts, $productsPerPage2);
        $rowIdxs = Functions::genPagesIndexes(
            $productsPerPage2, $productsPerRow2, 
            $totalProducts, $pageNumber2, $numPages
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
                    'productsPerPage' => $productsPerPage2, 
                    'productsPerRow' => $productsPerRow2, 
                    'totalProducts' => $totalProducts, 
                    'pageNumber' => $pageNumber2, 
                    'numPages' => $numPages, 
                    'rowIdxs' => $rowIdxs,
                    'currentPage' => $currentPage
                ]
            );
        }

        if ($pageNumber2 > -1) {
            // initializing the paginator
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
                'totalItems' => $totalProducts,
                'numRanges' => $numPages,
                'ranges' => $ranges,
                'currentRange' => [
                    'begin' => $firstItem,
                    'end' => $lastItem,
                    'index' => $pageNumber2 > -1 ? $pageNumber2 : 0,
                ],
            ];
            $numPagesPerPagingView = 4;
            $viewNumber = 0;
            $baseUrl = '';
            $pagingFor = '';
            $paginator2 = Page::genPagination(
                $pageNumber2 > -1 ? $pageNumber2 : 0, 
                $firstItem, $lastItem,
                $totalProducts, $ranges, $numPagesPerPagingView ,
                '', $viewNumber, $baseUrl
            );
            $paginator3 = Page::genPagination2(
                $pageNumber2, $productsPerPage2, $totalProducts, 
                $numPagesPerPagingView, $pagingFor, $viewNumber, 
                $baseUrl
            );
        } else {
            $paginator3 = [];
        }
        //dd("cont", $paginator, $currentPage, $products2);
            
    } else {
        $paginator3 = [];
    }
    //dd("cont", $paginator, $products2);

    if (false) {
        $numPagesPerPagingView = 4;
        $viewNumber = 0;
        $baseUrl = '';
        $pagingFor = '';
        /*
            $paginator2 = Page::genPagination(
                $pageNumber2 > -1 ? $pageNumber2 : 0, 
                $firstItem, $lastItem,
                $totalProducts, $ranges, $numPagesPerPagingView ,
                '', $viewNumber, $baseUrl
            );
        */ 
        $paginator3 = Page::genPagination2(
            $pageNumber2 > -1 ? $pageNumber2 : 0, 
            $productsPerPage2, $totalProducts, 
            $numPagesPerPagingView, $pagingFor, 
            $viewNumber, $baseUrl
        );
    }
    //dd($paginator3);
    
           
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
    @if(Functions::testVar($products2))
        
        @foreach ($rowIdxs[$currentPage] as $row)

            @php
                //dd($row);
            @endphp

            <div class="row product-list">
                @foreach ($row as $idx)

                    @php
                        //dd($idx);
                    @endphp

                    @component('lib.themewagon.product_mini')
                        {{-- 
                            slot 'extraOuterCss' added above... NOPE!!!
                        
                            @slot('extraOuterCss')
                                {{ "col-md-4 col-sm-6 col-xs-12" }}
                            @endslot

                        --}}
                        @if (!array_key_exists('extraOuterCss', $products2[$idx]) 
                            || empty($products2[$idx]['extraOuterCss']))
                            @slot('extraOuterCss')
                                {{ "col-md-4 col-sm-6 col-xs-12" }}
                            @endslot
                        @endif
                        @foreach ($products2[$idx] as $key => $value)
                            @slot($key)
                                {{ $value }}
                            @endslot
                        @endforeach
                        @slot('currency')
                            {!! $currency2 !!}
                        @endslot

                    @endcomponent

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

    @if (Functions::testVar($paginator3))
        @if (false)
            <div id="masterPagination"></div>
            <script>
                window.Laravel.pagination = '@json($paginator3)'
            </script>
        @else
            @component('lib.themewagon.paginator')
                @foreach ($paginator3 as $key => $val)
                    @slot($key)
                        @if ($val instanceof Htmlable) 
                            {!! $val->toHtml() !!}
                        @elseif (is_array($val) || is_object($val))
                            {!! serialize($val) !!}
                        @else
                            {!! $val !!}
                        @endif
                    @endslot
                @endforeach
            @endcomponent
        @endif
    @endif

</div>
<!-- END CONTENT -->