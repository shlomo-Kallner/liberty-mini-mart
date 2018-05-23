
@php
use \App\Utilities\Functions\Functions;

    // The DATA for the SLOTS of THIS COMPONENT are gathered HERE!!!  
    // Note: they CAN be empty... 

    $sorting2 = Functions::getBladedContent($sorting??'');
    $products2 = Functions::getBladedContent($products??'');
    $pageNumber2 = Functions::getBladedContent($pageNumber??-1,-1);
    // our default.. is 12 products per page (the template had 9..)
    $productsPerPage2 = Functions::getBladedContent($productsPerPage??12,12);

    {{-- NOTE: every product 'row' can hold up to 3 products! --}}
    // $productsPerRow2 = getBladedContent($productsPerRow,3);
    $productsPerRow2 = 3;

    // Some Utility Functions for the component..

    if(Functions::testVar($products2)){

        // adding the 'extraOuterCss' slot & data to the array..
        // so that we can use 'product_gallery.blade.php' for this..
        foreach ($products2 as $value){
            if(Functions::testVar($value) && is_array($value) 
            && !array_key_exists('extraOuterCss',$value)){
                $value['extraOuterCss'] = "col-md-4 col-sm-6 col-xs-12";
            }
        }

        // Initializing the row Indices while we are at it..
        $totalProducts = count($products2);
        $rowIdxs = Functions::genPagesIndexes($productsPerPage2, $productsPerRow2, $totalProducts, $pageNumber2);
        // if $productsPerPage is set then 
        //  EVEN IF $pageNumber2 IS NOT set then we have paganation!
        // THOUGH REALLY if we have more products than 
        //  $productsPerRow times $rowsPerPage then we have paganation
        // regardless...

        // initializing the paginator
        $numPages = Functions::genRowsPerPage($totalProducts, $productsPerPage2);
        if($numPages > 1){
            if($pageNumber2)
        }
        $paginator = [
            'totalItems' => $totalProducts,
            'numRanges' => $numPages,
            'ranges' => Functions::genRange(0, $numPages, 1),
            'currentRange' => [
                'begin' => $rowIdxs[0][0],
                'end' => $rowIdxs[count($rowIdxs)][count($rowIdxs[count($rowIdxs)])],
                'index' => 0,
            ],
        ];
            
    }

    
           
@endphp

<!-- BEGIN CONTENT -->
<div class="col-md-9 col-sm-7">

    @if (Functions::testVar($sorting2))
        <div class="row list-view-sorting clearfix">
            @if(false)
                {{-- 
                    This merely provides different views of the content, 
                    THIS IS AN ADVANCED_TASK_LIST Item => NOT YET IMPLEMENTED!
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
                <option value="#?limit=24" selected="selected">24</option>
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
                <option value="#?sort=p.model&amp;order=ASC">Model (A - Z)</option>
                <option value="#?sort=p.model&amp;order=DESC">Model (Z - A)</option>
                </select>
            </div>
            </div>
        </div>
        
    @endif

    <!-- BEGIN PRODUCT LIST -->
    @if(Functions::testVar($products2))
        
        @foreach ($rowIdxs as $row)

            @if (false)

                @php

                    // load up the row's products
                    // to be passed to the gallery..
                    // THIS is how we regulate the 
                    //  number of products per row
                    //  with 'lib.themewagon.product_gallery'!
                    $rowProducts = [];
                    foreach ($row as $idx){
                        $rowProducts[] = $products2[$idx];
                    }
                    
                @endphp
                    
                @component('lib.themewagon.product_gallery')
                    @slot('products')
                        @if('continue_escaping_$products' !== '')
                        {{ $rowProducts }}
                        @else
                        {!! $rowProducts !!}
                        @endif
                    @endslot
                    @slot('containerClasses')
                        {{ "row product-list" }}
                    @endslot
                    @slot('sizeClass')
                        {{ "col-md-12" }}
                    @endslot
                    @slot('productClass')
                        {{ "sale-product" }}
                    @endslot
                    @slot('owlClass')
                        {{ "owl-carousel5" }}
                    @endslot
                    @slot('title')
                        {{ "New Arrivals" }}
                    @endslot
                @endcomponent

            @else
                
                <div class="row product-list">
                    @foreach ($row as $idx)

                        @component('lib.themewagon.product_mini')
                        {{-- 
                            slot 'extraOuterCss' added above... 
                        
                            @slot('extraOuterCss')
                                {{ "col-md-4 col-sm-6 col-xs-12" }}
                            @endslot

                        --}}

                            @foreach ($products2[$idx] as $key => $value)
                                @slot($key)
                                    {{ $value }}
                                @endslot
                            @endforeach

                        @endcomponent

                    @endforeach
                </div>

            @endif
            
        @endforeach
        
    @else
        <div class="row product-list">
            <!-- PRODUCT ITEM START -->
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="product-item">
                    <div class="pi-img-wrapper">
                    <img src="assets/pages/img/products/model1.jpg" class="img-responsive" alt="Berry Lace Dress">
                    <div>
                        <a href="assets/pages/img/products/model1.jpg" class="btn btn-default fancybox-button">Zoom</a>
                        <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                    </div>
                    </div>
                    <h3><a href="shop-item.html">Berry Lace Dress Berry Lace Dress</a></h3>
                    <div class="pi-price">$29.00</div>
                    <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                </div>
            </div>
            <!-- PRODUCT ITEM END -->
            <!-- PRODUCT ITEM START -->
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="product-item">
                    <div class="pi-img-wrapper">
                    <img src="assets/pages/img/products/model2.jpg" class="img-responsive" alt="Berry Lace Dress">
                    <div>
                        <a href="assets/pages/img/products/model2.jpg" class="btn btn-default fancybox-button">Zoom</a>
                        <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                    </div>
                    </div>
                    <h3><a href="shop-item.html">Berry Lace Dress</a></h3>
                    <div class="pi-price">$29.00</div>
                    <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                </div>
            </div>
            <!-- PRODUCT ITEM END -->
            <!-- PRODUCT ITEM START -->
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="product-item">
                    <div class="pi-img-wrapper">
                    <img src="assets/pages/img/products/model6.jpg" class="img-responsive" alt="Berry Lace Dress">
                    <div>
                        <a href="assets/pages/img/products/model6.jpg" class="btn btn-default fancybox-button">Zoom</a>
                        <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                    </div>
                    </div>
                    <h3><a href="shop-item.html">Berry Lace Dress 2</a></h3>
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
                <img src="assets/pages/img/products/model4.jpg" class="img-responsive" alt="Berry Lace Dress">
                <div>
                    <a href="assets/pages/img/products/model4.jpg" class="btn btn-default fancybox-button">Zoom</a>
                    <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                </div>
                </div>
                <h3><a href="shop-item.html">Berry Lace Dress Berry Lace Dress</a></h3>
                <div class="pi-price">$29.00</div>
                <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
            </div>
            </div>
            <!-- PRODUCT ITEM END -->
            <!-- PRODUCT ITEM START -->
            <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="product-item">
                <div class="pi-img-wrapper">
                <img src="assets/pages/img/products/model5.jpg" class="img-responsive" alt="Berry Lace Dress">
                <div>
                    <a href="assets/pages/img/products/model5.jpg" class="btn btn-default fancybox-button">Zoom</a>
                    <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                </div>
                </div>
                <h3><a href="shop-item.html">Berry Lace Dress</a></h3>
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
                <img src="assets/pages/img/products/model3.jpg" class="img-responsive" alt="Berry Lace Dress">
                <div>
                    <a href="assets/pages/img/products/model3.jpg" class="btn btn-default fancybox-button">Zoom</a>
                    <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                </div>
                </div>
                <h3><a href="shop-item.html">Berry Lace Dress</a></h3>
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
                <img src="assets/pages/img/products/model7.jpg" class="img-responsive" alt="Berry Lace Dress">
                <div>
                    <a href="assets/pages/img/products/model7.jpg" class="btn btn-default fancybox-button">Zoom</a>
                    <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                </div>
                </div>
                <h3><a href="shop-item.html">Berry Lace Dress</a></h3>
                <div class="pi-price">$29.00</div>
                <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
            </div>
            </div>
            <!-- PRODUCT ITEM END -->
            <!-- PRODUCT ITEM START -->
            <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="product-item">
                <div class="pi-img-wrapper">
                <img src="assets/pages/img/products/model1.jpg" class="img-responsive" alt="Berry Lace Dress">
                <div>
                    <a href="assets/pages/img/products/model1.jpg" class="btn btn-default fancybox-button">Zoom</a>
                    <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                </div>
                </div>
                <h3><a href="shop-item.html">Berry Lace Dress</a></h3>
                <div class="pi-price">$29.00</div>
                <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
            </div>
            </div>
            <!-- PRODUCT ITEM END -->
            <!-- PRODUCT ITEM START -->
            <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="product-item">
                <div class="pi-img-wrapper">
                <img src="assets/pages/img/products/model2.jpg" class="img-responsive" alt="Berry Lace Dress">
                <div>
                    <a href="assets/pages/img/products/model2.jpg" class="btn btn-default fancybox-button">Zoom</a>
                    <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                </div>
                </div>
                <h3><a href="shop-item.html">Berry Lace Dress</a></h3>
                <div class="pi-price">$29.00</div>
                <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                <div class="sticker sticker-sale"></div>
            </div>
            </div>
            <!-- PRODUCT ITEM END -->
        </div>
    @endif
    <!-- END PRODUCT LIST -->
    @if(Functions::testVar($products2))
        @component('lib.themewagon.paginator')
            @slot('paginator')
                {!! $paginator !!}
            @endslot
        @endcomponent
    @endif
</div>
<!-- END CONTENT -->