
@php
    $testing = true;
    use \App\Utilities\Functions\Functions;
    use \App\Page;

    // The DATA for the SLOTS of THIS COMPONENT are gathered HERE!!!  
    // Note: they CAN be empty... 

    $products2 = Functions::getUnBladedContent($products??'', []);
    //dd($products, $products2);
    $currency2 = Functions::getBladedString($currency??'fa-usd','fa-usd');
    $sorting2 = Functions::getBladedContent($sorting??'', '');
    $pageNumber2 = intval(Functions::getBladedString($pageNumber??-1,-1));
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
        $rowIdxs = Functions::genPagesIndexes(
            $productsPerPage2, $productsPerRow2, 
            $totalProducts, $pageNumber2
        );
        $currentPage = $pageNumber2 > -1 ? $pageNumber2 : 0;
        // if $productsPerPage is set then 
        //  EVEN IF $pageNumber2 IS NOT set then we have paganation!
        // THOUGH REALLY if we have more products than 
        //  $productsPerRow times $rowsPerPage then we have paganation
        // regardless...
        // dd(
        //   $totalProducts, $rowIdxs, $productsPerPage2, 
        //   $productsPerRow2, $pageNumber2, $currentPage
        // );

        if ($pageNumber2 > -1) {
            // initializing the paginator
            //dd(
            //    count($rowIdxs[0]),
            //    $rowIdxs[0][count($rowIdxs[0]) -1],
            //    $rowIdxs[0][count($rowIdxs[0]) -1][count($rowIdxs[0][count($rowIdxs[0]) -1]) -1]
            //);
            //dd($rowIdxs[0][count($rowIdxs[0])][count($rowIdxs[0][count($rowIdxs[0])])]);
            $numPages = Functions::genRowsPerPage(
                $totalProducts, $productsPerPage2
            );
            $firstItem = $rowIdxs[0][0][0];
            $lastItem = $rowIdxs[0][count($rowIdxs[0]) -1][count($rowIdxs[0][count($rowIdxs[0]) -1]) -1];
            $paginator = [
                'totalItems' => $totalProducts,
                'numRanges' => $numPages,
                'ranges' => Functions::genRange(0, $numPages - 1, 1),
                'currentRange' => [
                    'begin' => $rowIdxs[0][0][0],
                    'end' => $rowIdxs[0][count($rowIdxs[0]) -1][count($rowIdxs[0][count($rowIdxs[0]) -1]) -1],
                    'index' => $pageNumber2 > -1 ? $pageNumber2 : 0,
                ],
            ];
            $paginator2 = Page::genPagination(
                int $pageNum, int $firstItemShownOnPage, int $lastItemShownOnPage,
                int $totalItems, array $rangeOfAllItemIndexes, int $numPagesPerPagingView = 4,
                string $pagingFor = '', int $viewNumber = 0, string $baseUrl = ''
            );
        } else {
            $paginator = [];
        }
        //dd("cont", $paginator, $currentPage, $products2);
            
    } else {
        $paginator = [];
    }
    //dd("cont", $paginator, $products2);

    
           
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
                    <option value="#?sort=p.sort_order&amp;order=ASC" selected="selected">Default</option>
                    <option value="#?sort=pd.name&amp;order=ASC">Name (A - Z)</option>
                    <option value="#?sort=pd.name&amp;order=DESC">Name (Z - A)</option>
                    <option value="#?sort=p.price&amp;order=ASC">Price (Low &gt; High)</option>
                    <option value="#?sort=p.price&amp;order=DESC">Price (High &gt; Low)</option>
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
        
    @else
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
    @endif
    <!-- END PRODUCT LIST -->

    @if (Functions::testVar($paginator))
        @component('lib.themewagon.paginator')
            @foreach ($paginator as $key => $val)
                @slot($key)
                    {!! serialize($val) !!}
                @endslot
            @endforeach
        @endcomponent
    @endif

</div>
<!-- END CONTENT -->